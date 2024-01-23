<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config as config;
use App\User as UserModel;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserSettingsPassTest extends TestCase
{
    use RefreshDatabase;

    protected $config;


    protected function setUp(): void
    {
        parent::setUp();

        $this->config = config('calories.constants');
    }

    /**
     * User Settings Tests
     *
     * @return void
     */
    public function testUserSettings()
    {

        // Create new user test
        $response_create = $this->post('/api/user/store', [
            "name" => "Test User", 
            "email" => $this->config['TEST_USER']['email'],
            "password" => $this->config['TEST_USER']['password'],
            "password_confirmation" => $this->config['TEST_USER']['password'],
            "role" => "subscriber"
        ]);

        $response_create->assertStatus(200)->assertJson([
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

        // Get user data  
        $user = UserModel::where('email', $this->config['TEST_USER']['email'])->first();

        // Get created user settings
        $response_get = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('api/usersettings/' . $user['id']);

        $response_get->assertStatus(200);

        // Update user settings test
        $response_update = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/usersettings/updateByUserId', [
            "daily_calories" => 1500, 
            "monthly_budget" => 1200
        ]);

        $response_update->assertStatus(200)->assertJson([
            'message' => 'User settings updated successfully.',
        ]);
    }
}
