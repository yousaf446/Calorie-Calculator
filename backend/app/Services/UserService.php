<?php

namespace App\Services;

use App\User as UserModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\UserSettingsService;
use \Config;

class UserService
{
    private $user_repository;

    private $user_settings_service;

    private $calories_constants;

    public function __construct(UserRepositoryInterface $user_repository,
     UserSettingsService $user_settings_service)
    {
        $this->user_repository = $user_repository;
        $this->user_settings_service = $user_settings_service;
        $this->calories_constants = Config::get('calories.constants');
    }

    /**
     * Finds all users.
     *
     * @return UserModel[]
     */
    public function findAll()
    {
        $users =  $this->user_repository->all();
        return $users;
    }



    /**
     * Find user by id.
     *
     * @param  uuid $id
     * @return UserModel
     */
    public function findById($id)
    {
        $user = $this->user_repository->findById($id);
        return $user;
    }

    /**
     * Create a new user.
     *
     * @param  array $data
     * @return UserModel
     */
    public function store($data)
    {
        $user = $this->user_repository->store($data);

        if ($user['role'] != $this->calories_constants['ROLES']['ADMIN']) {
            $user_settings = [
                'user_id' => $user['id'],
                'daily_calories' => $this->calories_constants['DEFAULT_DAILY_CALORIES_THRESHOLD'],
                'monthly_budget' => $this->calories_constants['DEFAULT_MONTHLY_BUDGET'],
            ];
    
            $user_settings = $this->user_settings_service->store($user_settings);
        }

        return $user;
    }


    /**
     * Updates an existing user by id.
     *
     * @param  uuid $id
     * @param  array $data
     * @return UserModel
     */
    public function update($id, $data)
    {
        $user = $this->user_repository->update($id, $data);
        return $user;
    }

    /**
     * Delete an existing user by id.
     *
     * @param  uuid $id
     * @return bool
     */
    public function delete($id)
    {
        $deleted = $this->user_repository->delete($id);
        return $deleted;
    }

    /**
     * Get users count by role
     *
     * @param  string $role
     * @return bool
     */
    public function getUsersCountByRole($role)
    {
        $count = $this->user_repository->getUsersCountByRole($role);
        return $count;
    }
}
