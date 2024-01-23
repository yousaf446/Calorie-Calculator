<?php

namespace App\Repositories\Interfaces;
use Illuminate\Http\Request;

interface FoodEntryRepositoryInterface
{
    public function all();
    public function findByUserId($user_id);
    public function findById($id);
    public function findOneByUserId($user_id, $id);
    public function store($data);
    public function update($id, $data);
    public function updateByUser($user_id, $id, $data);
    public function delete($id);
    public function deleteByUser($user_id, $id);
    public function getUserTotalCaloriesByDay($user_id, $day);
    public function getUserSpendBudgetByMonth($user_id, $month);
    public function entriesAddedByDate($from, $to);
    public function caloriesAddedByDate($from, $to);
    public function getCaloriesPerUser($from, $to);
}