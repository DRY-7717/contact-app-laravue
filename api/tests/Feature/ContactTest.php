<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateContactSuccess(): void
    {
        $this->seed([UserSeeder::class]);
        $this->post(
            '/api/contacts',
            [
                'first_name' => 'Bima Arya',
                'last_name' => 'Wicaksana',
                'email' => 'wicaksanabimaarya@gmail.com',
                'phone' => '089638307725'

            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(201)
            ->assertJson([
                'data' => [
                    'first_name' => 'Bima Arya',
                    'last_name' => 'Wicaksana',
                    'email' => 'wicaksanabimaarya@gmail.com',
                    'phone' => '089638307725'
                ]
            ]);
    }
    public function testCreateContactIsRequiredOrFailed(): void
    {
        $this->seed([UserSeeder::class]);
        $this->post(
            '/api/contacts',
            [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone' => ''

            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "first_name" => [
                        "The first name field is required."
                    ],
                    "last_name" => [
                        "The last name field is required."
                    ],
                    "email" => [
                        "The email field is required."
                    ],
                    "phone" => [
                        "The phone field is required."
                    ],
                ]
            ]);
    }
    public function testCreateContactUnauthenticated(): void
    {
        $this->seed([UserSeeder::class]);
        $this->post(
            '/api/contacts',
            [
                'first_name' => 'Bima Arya',
                'last_name' => 'Wicaksana',
                'email' => 'wicaksanabimaarya@gmail.com',
                'phone' => '089638307725'

            ],
            [
                'Authorization' => ''
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


    public function testGetContactSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Bima Arya',
                    'last_name' => 'Wicaksana',
                    'email' => 'wicaksanabimaarya@gmail.com',
                    'phone' => '089638307725'
                ]
            ]);
    }
    public function testGetContactFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id .= 'me', [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."

            ]);
    }
    public function testGetContactOtherUser()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => 'test2'
        ])->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."

            ]);
    }

    public function testUpdateContactSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put(
            '/api/contacts/' . $contact->id,
            [
                'first_name' => 'Bima Arya',
                'last_name' => 'Wicaksana',
                'email' => 'wicaksanabimaarya@gmail.com',
                'phone' => '089638307725'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Bima Arya',
                    'last_name' => 'Wicaksana',
                    'email' => 'wicaksanabimaarya@gmail.com',
                    'phone' => '089638307725'
                ]
            ]);
    }
    public function testUpdateContactIsRequired()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put(
            '/api/contacts/' . $contact->id,
            [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone' => ''
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "first_name" => [
                        "The first name field is required."
                    ],
                    "last_name" => [
                        "The last name field is required."
                    ],
                    "email" => [
                        "The email field is required."
                    ],
                    "phone" => [
                        "The phone field is required."
                    ],
                ]
            ]);
    }
    public function testUpdateContactIdNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put(
            '/api/contacts/' . $contact->id .= "me",
            [
                'first_name' => 'Bima Arya',
                'last_name' => 'Wicaksana',
                'email' => 'wicaksanabimaarya@gmail.com',
                'phone' => '089638307725'
            ],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."
            ]);
    }
    public function testUpdateContactOtherUser()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->put(
            '/api/contacts/' . $contact->id,
            [
                'first_name' => 'Bima Arya',
                'last_name' => 'Wicaksana',
                'email' => 'wicaksanabimaarya@gmail.com',
                'phone' => '089638307725'
            ],
            [
                'Authorization' => 'test2'
            ]
        )->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."
            ]);
    }

    public function testDeleteContactSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete(
            '/api/contacts/' . $contact->id,
            [],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(200)
            ->assertJson([
                "message" => "Contact has been deleted"
            ]);
    }
    public function testDeleteContactIdNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete(
            '/api/contacts/' . $contact->id .= "me",
            [],
            [
                'Authorization' => 'test'
            ]
        )->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."
            ]);
    }
    public function testDeleteContactOtherUser()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->delete(
            '/api/contacts/' . $contact->id,
            [],
            [
                'Authorization' => 'test2'
            ]
        )->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."
            ]);
    }
}
