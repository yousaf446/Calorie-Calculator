<?php

namespace App\Repositories;

use App\User as UserModel;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get all users
     */
    public function all()
    {
        return UserModel::orderBy('created_At', 'DESC')->get();
    }

    /**
     * Get user by id
     */
    public function findById($id)
    {
        return UserModel::with(['userSettings', 'foodEntries'])->get()->find($id);
        
    }

    /**
     * Add new user
     */
    public function store($data)
    {
        return UserModel::create($data);
    }

    /**
     * Update existing user
     */
    public function update($id, $data)
    {
        return UserModel::where('id', $id)->update([
            'name' => $data['name'],
            'role' => $data['role']
        ]);
    }

    /**
     * Delete existing user
     */
    public function delete($id)
    {
        return UserModel::find($id)->delete();
    }

    /**
     * Get users count by role
     */
    public function getUsersCountByRole($role)
    {
        return UserModel::where('role', $role)->count();
    }
}