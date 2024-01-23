<?php

namespace App\Repositories;

use App\UserSettings as UserSettingsModel;
use App\Repositories\Interfaces\UserSettingsRepositoryInterface;

class UserSettingsRepository implements UserSettingsRepositoryInterface
{

    /**
     * Get user settings by user id
     */
    public function findByUserId($user_id)
    {
        return UserSettingsModel::where('user_id', $user_id)->get();
        
    }

    /**
     * Add new user settings
     */
    public function store($data)
    {
        return UserSettingsModel::create($data);
    }

    /**
     * Update existing user settings
     */
    public function update($id, $data)
    {
        return UserSettingsModel::where('id', $id)->update([
            'daily_calories' => $data['daily_calories'],
            'monthly_budget' => $data['monthly_budget']
        ]);
    }

    /**
     * Update existing user settings by user id
     */
    public function updateByUserId($user_id, $data)
    {
        return UserSettingsModel::where('user_id', $user_id)->update([
            'daily_calories' => $data['daily_calories'],
            'monthly_budget' => $data['monthly_budget']
        ]);
    }
}