<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess(): void
    {
        $this->post('/api/users', [
            "name" => "Bima Arya Wicaksana",
            "username" => "wicaksanabimaarya",
            "password" => "rahasia",
        ])->assertStatus(201)
            ->assertJson([
                "data" => [
                    "name" => "Bima Arya Wicaksana",
                    "username" => "wicaksanabimaarya",
                ]
            ]);
    }
    public function testRegisterFailed(): void
    {
        $this->post('/api/users', [
            "name" => "",
            "username" => "",
            "password" => "",
        ])->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "name" => [
                        "The name field is required."
                    ],
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                ]
            ]);
    }
    public function testRegisterUsernameAlreadyExist(): void
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            "name" => "Bima Arya Wicaksana",
            "username" => "wicaksanabimaarya",
            "password" => "rahasia",
        ])->assertStatus(409)
            ->assertJson([
                "errors" => [
                    "username" => [
                        "username already registered!"
                    ]
                ]
            ]);
    }


    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            "username" => "test",
            "password" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "name" => "test",
                    "username" => "test",
                ]
            ]);

        $user = User::where('username', 'test')->first();
        self::assertNotNull($user->token);
    }
    public function testLoginFailedPasswordWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            "username" => "test",
            "password" => "rahasia"
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'username or password wrong!.'
                    ]
                ]
            ]);
    }
    public function testLoginFailedUsernameWrong()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            "username" => "rahasia",
            "password" => "test"
        ])->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'username or password wrong!.'
                    ]
                ]
            ]);
    }
    public function testLoginIsRequired()
    {
        $this->post('/api/users/login', [
            "username" => "",
            "password" => ""
        ])->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "username" => [
                        "The username field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                ]
            ]);
    }
}
