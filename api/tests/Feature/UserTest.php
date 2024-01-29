<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

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

    public function testGetCurrentUserSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current', [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'test',
                    'username' => 'test'
                ]
            ]);
    }

    public function testUnauthenticated()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current')->assertStatus(403)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Unauthecticated'
                    ]
                ]
            ]);
    }

    public function testGetInvalidToken()
    {
        $this->seed([UserSeeder::class]);
        $this->get('/api/users/current', [
            'Authorization' => 'rahasia'
        ])->assertStatus(403)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Unauthecticated'
                    ]
                ]
            ]);
    }

    public function testUserCurrentUpdate()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch(
            '/api/users/current',
            [
                'name' => 'bima',
                'password' => 'test'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'bima',
                    'username' => 'test'
                ]
            ]);

        $newUser = User::where('username', 'test')->first();
        self::assertNotEquals($oldUser->name, $newUser->name);
        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUserCurrentUpdateIsRequired()
    {
        $this->seed([UserSeeder::class]);
        $this->patch(
            '/api/users/current',
            [
                'name' => '',
                'password' => ''
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "name" => [
                        "The name field is required."
                    ],
                    "password" => [
                        "The password field is required."
                    ],
                ]
            ]);
    }

    public function testLogoutSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(
            'api/users/logout',
            [],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'message' => 'logout success'
            ]);

        $user = User::where('username', 'test')->first();
        assertNull($user->token);
    }
    public function testLogoutFailed()
    {
        $this->seed([UserSeeder::class]);

        $this->delete(
            'api/users/logout',
            [],
            [
                'Authorization' => 'salah'
            ]
        )->assertStatus(403)
            ->assertJson([
                'errors' => [
                    'message' => [
                        'Unauthecticated'
                    ]
                ]
            ]);
    }
}
