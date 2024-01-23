<?php

namespace App\Repositories;

use App\FoodEntry as FoodEntryModel;
use App\Repositories\Interfaces\FoodEntryRepositoryInterface;
use \DB;

class FoodEntryRepository implements FoodEntryRepositoryInterface
{
    /**
     * Get all food entries
     */
    public function all()
    {
        return FoodEntryModel::with('user')->orderBy('consumed_at', 'DESC')->get();
    }

    /**
     * Get food entries by user id
     */
    public function findByUserId($user_id)
    {
        return FoodEntryModel::where('user_id', $user_id)->orderBy('consumed_at', 'DESC')->get();
        
    }

    /**
     * Get food entry by id
     */
    public function findById($id)
    {
        return FoodEntryModel::with('user')->where('id', $id)->get();
        
    }

    /**
     * Get one food entry by id and user_id
     */
    public function findOneByUserId($user_id, $id)
    {
        return FoodEntryModel::with('user')->where('user_id', $user_id)->where('id', $id)->get();
        
    }

    /**
     * Add new food entry
     */
    public function store($data)
    {
        return FoodEntryModel::create($data);
    }

    /**
     * Update existing food entry
     */
    public function update($id, $data)
    {
        return FoodEntryModel::where('id', $id)->update([
            'product_name' => $data['product_name'],
            'calorie_value' => $data['calorie_value'],
            'price' => $data['price'],
            'consumed_at' => $data['consumed_at'],
        ]);
    }

    /**
     * Update existing food entry by user id
     */
    public function updateByUser($user_id, $id, $data)
    {
        return FoodEntryModel::where('id', $id)->where('user_id', $user_id)->update([
            'product_name' => $data['product_name'],
            'calorie_value' => $data['calorie_value'],
            'price' => $data['price'],
            'consumed_at' => $data['consumed_at'],
        ]);
    }

    /**
     * Delete existing food entry
     */
    public function delete($id)
    {
        return FoodEntryModel::find($id)->delete();
    }

    /**
     * Delete existing food entry by user id
     */
    public function deleteByUser($user_id, $id)
    {
        return FoodEntryModel::where('id', $id)->where('user_id', $user_id)->delete();
    }

    /**
     * Get user total calories by day
     */
    public function getUserTotalCaloriesByDay($user_id, $day)
    {
        return FoodEntryModel::select(
            "user_id",
            DB::raw("(sum(calorie_value)) as calories_consumed")
            )
            ->where('user_id', $user_id)
            ->whereDay('consumed_at', $day)
            ->groupBy('user_id')
            ->get();
    }

    /**
     * Get user budget spend by month
     */
    public function getUserSpendBudgetByMonth($user_id, $month)
    {
        return FoodEntryModel::select(
            "user_id",
            DB::raw("(sum(price)) as budget_spent")
            )
            ->where('user_id', $user_id)
            ->whereMonth('consumed_at', $month)
            ->groupBy('user_id')
            ->get();
    }

    /**
     * Get entries by date
     */
    public function entriesAddedByDate($from, $to)
    {
        return FoodEntryModel::whereBetween('consumed_at', [$from, $to])->count();
    }

    /**
     * Get calories per user
     */
    public function getCaloriesPerUser($from, $to)
    {
        return DB::table('food_entry')
        ->join('users','food_entry.user_id', '=', 'users.id')
        ->select('food_entry.user_id', 'users.name', DB::raw("FORMAT((sum(food_entry.calorie_value)/7),2) as calories_added"))
        ->whereBetween('food_entry.consumed_at', [$from, $to])
        ->groupBy('food_entry.user_id', 'users.name')
        ->get();
    }

     /**
     * Average calories added per user
     */
    public function caloriesAddedByDate($from, $to)
    {
        return FoodEntryModel::select(
            DB::raw("FORMAT((sum(calorie_value)/COUNT(DISTINCT(user_id))),2) as calories_average")
            )
            ->whereBetween('consumed_at', [$from, $to])
            ->get();
    }

    // public function caloriesAddedByDate($from, $to)
    // {
    //     return FoodEntryModel::select(
    //         DB::raw("FORMAT((sum(calorie_value)),2) as calories_added")
    //         )
    //         ->whereBetween('consumed_at', [$from, $to])
    //         ->get();
    // }
}