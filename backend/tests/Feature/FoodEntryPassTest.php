<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config as config;
use App\FoodEntry as FoodEntryModel;
use Tymon\JWTAuth\Facades\JWTAuth;

class FoodEntryTest extends TestCase
{
    use RefreshDatabase;

    protected $config;


    protected function setUp(): void
    {
        parent::setUp();

        $this->config = config('calories.constants');
    }

    /**
     * Food entry Tests
     *
     * @return void
     */
    public function testFoodEntry()
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

        // Create new food entry test
        $response_create = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/foodentry/store', [
            "product_name" => "burger",
            "calorie_value" => 100,
            "consumed_at" => "2022-06-10 00:00:20",
            "price" => 6
        ]);

        $response_create->assertStatus(200)->assertJson([
            'message' => 'FoodEntry created successfully.',
        ]);

        // Get all food entries test
        $response_get = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/foodentry/all');

        $response_get->assertStatus(200);

        $food_entry = $response_get->decodeResponseJson()[0];

        // Update created foodentry test
        $response_update = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/foodentry/update', [
            "id" => $food_entry['id'],
            "product_name" => "burger",
            "calorie_value" => 200,
            "consumed_at" => "2022-06-12 00:00:20",
            "price" => 8
        ]);

        $response_update->assertStatus(200)->assertJson([
            'message' => 'Food entry updated successfully.',
        ]);
        
        // Delete created foodentry test
        $response_delete = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete('/api/foodentry/delete/' . $food_entry['id']);

        $response_delete->assertStatus(200)->assertJson([
            'message' => 'Food entry deleted successfully.',
        ]);
    }
}
