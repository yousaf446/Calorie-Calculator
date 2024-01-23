<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config as config;
use App\FoodEntry as FoodEntryModel;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateFoodEntryFailTest extends TestCase
{
    use RefreshDatabase;

    protected $config;


    protected function setUp(): void
    {
        parent::setUp();

        $this->config = config('calories.constants');
    }

    /**
     * Create Food entry fail Tests
     *
     * @return void
     */
    public function testCreateFoodEntryFail()
    {

        // Create new user test
        $response_create_user = $this->post('/api/user/store', [
            "name" => "Test User", 
            "email" => $this->config['TEST_USER']['email'],
            "password" => $this->config['TEST_USER']['password'],
            "password_confirmation" => $this->config['TEST_USER']['password'],
            "role" => "subscriber"
        ]);

        $response_create_user->assertStatus(200)->assertJson([
            'message' => 'User created successfully.',
        ]);

        // Login created user test
        $response_login = $this->post('/api/login', [
            "email" => $this->config['TEST_USER']['email'],
            "password"=> $this->config['TEST_USER']['password'],
        ]);

        
        $response_login->assertStatus(200)->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);

        // API Token for further requests
        $token = $response_login->decodeResponseJson()['access_token'];

        // Create new food entry invalid calorie value test
        $response_create_invalid_calorie_value = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/foodentry/store', [
            "product_name" => "burger",
            "calorie_value" => "abc",
            "consumed_at" => "2022-06-10 00:00:20",
            "price" => 6
        ]);

        $response_create_invalid_calorie_value->assertStatus(400)->assertJson([
            'message' => 'Bad request!',
        ]);

        // Create new food entry invalid consumed date test
        $response_create_invalid_consumed_date = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/foodentry/store', [
            "product_name" => "burger",
            "calorie_value" => 100,
            "consumed_at" => "2022",
            "price" => 6
        ]);

        $response_create_invalid_consumed_date->assertStatus(400)->assertJson([
            'message' => 'Bad request!',
        ]);
    }
}
