<?php

namespace App\Repositories\Interfaces;
use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function all();
    public function findById($id);
    public function store($data);
    public function update($id, $data);
    public function delete($id);
    public function getUsersCountByRole($role);
}