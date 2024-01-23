<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\FoodEntryRepository;
use App\Repositories\Interfaces\FoodEntryRepositoryInterface;

use App\Repositories\UserRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;

use App\Repositories\UserSettingsRepository;
use App\Repositories\Interfaces\UserSettingsRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            FoodEntryRepositoryInterface::class, 
            FoodEntryRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class, 
            UserRepository::class
        );

        $this->app->bind(
            UserSettingsRepositoryInterface::class, 
            UserSettingsRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
