<?php

namespace App\Repositories\Interfaces;
use Illuminate\Http\Request;

interface UserSettingsRepositoryInterface
{
    public function findByUserId($user_id);
    public function store($data);
    public function update($id, $data);
    public function updateByUserId($user_id, $data);
}