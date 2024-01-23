<?php

namespace App\Services;

use App\FoodEntry as FoodEntryModel;
use App\Repositories\Interfaces\FoodEntryRepositoryInterface;

use App\Services\UserSettingsService;
use App\Services\UserService;
use \Config;

class FoodEntryService
{
    private $foodentry_repository;
    private $user_settings_service;
    private $user_service;
    private $calories_constants;

    public function __construct(FoodEntryRepositoryInterface $foodentry_repository,
     UserSettingsService $user_settings_service,
     UserService $user_service)
    {
        $this->foodentry_repository = $foodentry_repository;
        $this->user_settings_service = $user_settings_service;
        $this->user_service = $user_service;
        $this->calories_constants = Config::get('calories.constants');
    }

    /**
     * Finds all food entries.
     *
     * @return FoodEntryModel[]
     */
    public function findAll()
    {
        $food_entries =  $this->foodentry_repository->all();
        return $food_entries;
    }

    /**
     * Finds food entries by user id.
     *
     * @return FoodEntryModel[]
     */
    public function findByUserId($user_id)
    {
        $food_entries =  $this->foodentry_repository->findByUserId($user_id);
        return $food_entries;
    }

    /**
     * Finds one food entry by id.
     *
     * @param  uuid $id
     * @return FoodEntryModel
     */
    public function findById($id)
    {
        $food_entry = $this->foodentry_repository->findById($id);
        if (count($food_entry) > 0) {
            return $food_entry[0];
        } else {
            return false;
        }
    }

    /**
     * Finds one food entry by id and user id.
     *
     * @param  uuid $user_id
     * @param  uuid $id
     * @return FoodEntryModel
     */
    public function findOneByUserId($user_id, $id)
    {
        $food_entry = $this->foodentry_repository->findOneByUserId($user_id, $id);
        if (count($food_entry) > 0) {
            return $food_entry[0];
        } else {
            return false;
        }
    }

    /**
     * Create a new food entry.
     *
     * @param  array $data
     * @return FoodEntryModel
     */
    public function store($data)
    {
        $food_entry = $this->foodentry_repository->store($data);
        return $food_entry;
    }


    /**
     * Updates an existing FoodEntry by id.
     *
     * @param  uuid $id
     * @param  array $data
     * @return FoodEntryModel
     */
    public function update($id, $data)
    {
        $food_entry = $this->foodentry_repository->update($id, $data);
        return $food_entry;
    }

    /**
     * Updates an existing FoodEntry by id and user id.
     * @param  uuid $user_id
     * @param  uuid $id
     * @param  array $data
     * @return FoodEntryModel
     */
    public function updateByUser($user_id, $id, $data)
    {
        $food_entry = $this->foodentry_repository->updateByUser($user_id, $id, $data);
        return $food_entry;
    }

    /**
     * Delete an existing FoodEntry by id.
     *
     * @param  uuid $id
     * @return bool
     */
    public function deleteById($id)
    {
        $deleted = $this->foodentry_repository->delete($id);
        return $deleted;
    }

    /**
     * Delete an existing FoodEntry by id and user id.
     *
     * @param uuid $user_id
     * @param  uuid $id
     * @return bool
     */
    public function deleteByUser($user_id, $id)
    {
        $deleted = $this->foodentry_repository->deleteByUser($user_id, $id);
        return $deleted;
    }

    /**
     * Get user constraints
     *
     * @param  uuid $user_id
     * @param  uuid $date
     * @return bool
     */
    public function userConstraints($user_id, $date)
    {
        $timestamp = strtotime($date);
        $day = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year = date('Y', $timestamp);


        $data = [
            'user_id' => $user_id,
            'date' => $date,
            'calories_consumed' => 0,
            'budget_spent' => 0,
            'user_settings' => $this->user_settings_service->findByUserId($user_id)[0]
        ];

        $calories_consumed = $this->foodentry_repository->getUserTotalCaloriesByDay($user_id, $day);
        $budget_spend = $this->foodentry_repository->getUserSpendBudgetByMonth($user_id, $month);
        if (count($calories_consumed) > 0) {
            $data['calories_consumed'] = $calories_consumed[0]['calories_consumed'];
        } 
        if (count($budget_spend) > 0) {
            $data['budget_spent'] = $budget_spend[0]['budget_spent'];
        } 

        return $data;
    }

    /**
     * Get admin report
     *
     * @return bool
     */
    public function adminReport($date)
    {
        $seven_days_before_start = date('Y-m-d', strtotime('-6 days'));
        $fourteen_days_before_start = date('Y-m-d', strtotime('-13 days'));
        $fourteen_days_before_end = date('Y-m-d', strtotime('-7 days'));

        $seven_days_before_start .= ' 00:00:00';
        $fourteen_days_before_end .= ' 23:59:59';

        $data = ['foodentries_last7days' => 0, 'foodentries_7daysbefore' => 0, 'avg_calories_added_last7days' => 0];

        $foodentries_last7days = $this->foodentry_repository->entriesAddedByDate($seven_days_before_start, $date);
        $foodentries_7daysbefore = $this->foodentry_repository->entriesAddedByDate($fourteen_days_before_start, $fourteen_days_before_end);
        
        $calories_avg_per_user_last7days = $this->foodentry_repository->caloriesAddedByDate($seven_days_before_start, $date);

        // $calories_last7days = $this->foodentry_repository->caloriesAddedByDate($seven_days_before_start, $date);
        // $subscriber_count = $this->user_service->getUsersCountByRole($this->calories_constants['ROLES']['SUBSCRIBER']);
        
        // $data['avg_calories_added_per_user_last7days'] = $this->foodentry_repository->getCaloriesPerUser($seven_days_before_start, $date);
        
        if ($foodentries_last7days > 0) {
            $data['foodentries_last7days'] = $foodentries_last7days;
        } 
        if ($foodentries_7daysbefore > 0) {
            $data['foodentries_7daysbefore'] = $foodentries_7daysbefore;
        } 
        if (count($calories_avg_per_user_last7days) > 0) {
            $data['avg_calories_added_last7days'] = ($calories_avg_per_user_last7days[0]['calories_average'] == null) ? 0 : $calories_avg_per_user_last7days[0]['calories_average'];
        }
        // if (count($calories_last7days) > 0) {
        //     $calories_last7days = $calories_last7days[0]['calories_added'];
        // } else {
        //     $calories_last7days = 0;
        // }
        // $data['avg_calories_added_last7days'] = $calories_last7days / $subscriber_count;
        return $data;
    }
}
