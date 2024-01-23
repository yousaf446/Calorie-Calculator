<?php

namespace App\Services;

use App\UserSettings as UserSettingsModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\UserSettingsRepositoryInterface;

class UserSettingsService
{
    private $user_settings_repository;

    public function __construct(UserSettingsRepositoryInterface $user_settings_repository)
    {
        $this->user_settings_repository = $user_settings_repository;
    }

    /**
     * Finds user setting by user id.
     *
     * @param  uuid $user_id
     * @return UserSettingsModel
     */
    public function findByUserId($user_id)
    {
        $user_settings = $this->user_settings_repository->findByUserId($user_id);
        return $user_settings;
    }

    /**
     * Create a new user settings.
     *
     * @param  array $data
     * @return UserSettingsModel
     */
    public function store($data)
    {
        $user_settings = $this->user_settings_repository->store($data);
        return $user_settings;
    }


    /**
     * Updates an existing UserSettings by id.
     *
     * @param  uuid $id
     * @param  array $data
     * @return UserSettingsModel
     */
    public function update($id, $data)
    {
        $user_settings = $this->user_settings_repository->update($id, $data);
        return $user_settings;
    }

    /**
     * Updates an existing UserSettings by user id.
     *
     * @param  uuid $user_id
     * @param  array $data
     * @return UserSettingsModel
     */
    public function updateByUserId($user_id, $data)
    {
        $user_settings = $this->user_settings_repository->updateByUserId($user_id, $data);
        return $user_settings;
    }
}
