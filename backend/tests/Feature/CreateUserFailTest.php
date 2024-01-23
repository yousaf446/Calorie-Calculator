<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Config as config;
use App\User as UserModel;
use Tymon\JWTAuth\Facades\JWTAuth;

class CreateUserFailTest extends TestCase
{
    use RefreshDatabase;

    protected $config;


    protected function setUp(): void
    {
        parent::setUp();

        $this->config = config('calories.constants');
    }

    /**
     * Create User fail Tests
     *
     * @return void
     */
    public function testCreateUserFail()
    {

        // Create new user invalid email test
        $response_create_invalid_email = $this->post('/api/user/store', [
            "name" => "Test User", 
            "email" => "abc@",
            "password" => $this->config['TEST_USER']['password'],
            "password_confirmation" => $this->config['TEST_USER']['password'],
            "role" => "subscriber"
        ]);

        $response_create_invalid_email->assertStatus(400)->assertJson([
            'message' => 'Bad request!',
        ]);

        // Create new user invalid role test
        $response_create_invalid_role = $this->post('/api/user/store', [
            "name" => "Test User", 
            "email" => "abc@",
            "password" => $this->config['TEST_USER']['password'],
            "password_confirmation" => $this->config['TEST_USER']['password'],
            "role" => "subscriber2"
        ]);

        $response_create_invalid_role->assertStatus(400)->assertJson([
            'message' => 'Bad request!',
        ]);
    }
}
