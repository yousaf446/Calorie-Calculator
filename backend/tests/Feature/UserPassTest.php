<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Config as config;
use App\User as UserModel;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserPassTest extends TestCase
{
    use RefreshDatabase;

    protected $config;


    protected function setUp(): void
    {
        parent::setUp();

        $this->config = config('calories.constants');
    }

    /**
     * User Tests
     *
     * @return void
     */
    public function testUser()
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

        // Update user data test

        $response_update = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->put('/api/user/update', [
            "id" => $user['id'],
            "name" => "Test User2", 
            "role" => "subscriber"
        ]);

        $response_update->assertStatus(200)->assertJson([
            'message' => 'User updated successfully.',
        ]);

        // Get created user test
        $response_get = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('api/user/');

        $response_get = $this->get('api/user/' . $user['id']);

        $response_get->assertStatus(200);
        
        // Delete created user test
        $response_delete = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->delete('/api/user/delete/' . $user['id']);

        $response_delete->assertStatus(200)->assertJson([
            'message' => 'User deleted successfully.',
        ]);
    }
}
