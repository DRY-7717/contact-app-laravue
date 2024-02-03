<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AddressTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->post('/api/contacts/' . $contact->id . '/addresses', [
            'street' => 'Jl. Meruyung Raya',
            'city' => 'Depok',
            'province' => 'Jawa Barat',
            'country' => 'Indonesia',
            'postal_code' => '16515',
            'contact_id' => $contact->id
        ], [
            'Authorization' => 'test'
        ])->assertStatus(201)
            ->assertJson([
                'data' => [
                    'street' => 'Jl. Meruyung Raya',
                    'city' => 'Depok',
                    'province' => 'Jawa Barat',
                    'country' => 'Indonesia',
                    'postal_code' => '16515',
                    'contact_id' => $contact->id
                ]
            ]);
    }
    public function testCreateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->post('/api/contacts/' . $contact->id . '/addresses', [
            'street' => '',
            'city' => '',
            'province' => '',
            'country' => '',
            'postal_code' => '',
            'contact_id' => ''
        ], [
            'Authorization' => 'test'
        ])->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "contact_id" => [
                        "The contact id field is required."
                    ],
                    "street" => [
                        "The street field is required."
                    ],
                    "city" => [
                        "The city field is required."
                    ],
                    "province" => [
                        "The province field is required."
                    ],
                    "country" => [
                        "The country field is required."
                    ],
                    "postal_code" => [
                        "The postal code field is required."
                    ]
                ]
            ]);
    }
    public function testContactNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);
        $contact = Contact::query()->limit(1)->first();

        $this->post('/api/contacts/' . $contact->id .= 'me' . '/addresses', [
            'street' => 'Jl. Meruyung Raya',
            'city' => 'Depok',
            'province' => 'Jawa Barat',
            'country' => 'Indonesia',
            'postal_code' => '16515',
            'contact_id' => $contact->id .= 'me'
        ], [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."

            ]);
    }

    public function testGetAddressSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'street' => 'Jl. Meruyung Raya',
                    'city' => 'Depok',
                    'province' => 'Jawa Barat',
                    'country' => 'Indonesia',
                    'postal_code' => '16515',
                    'contact_id' => $address->contact_id
                ]
            ]);
    }
    public function testGetAddressContactIdNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . $address->contact_id .= 'me' . '/addresses/' . $address->id, [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."
            ]);
    }
    public function testGetAddressAddressIdNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id .= 'me', [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."
            ]);
    }
    public function testGetAddressNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $address = Address::query()->limit(1)->first();

        $this->get('/api/contacts/' . '' . '/addresses/' . '', [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                "message" => "Data not found."
            ]);
    }


    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            'street' => 'Jl. Meruyung Raya',
            'city' => 'Depok',
            'province' => 'Jawa Utara',
            'country' => 'Indonesia',
            'postal_code' => '16515',
            'contact_id' => $address->contact_id
        ], [
            'Authorization' => 'test'
        ])->assertStatus(200)
            ->assertJson([
                'data' => [
                    'street' => 'Jl. Meruyung Raya',
                    'city' => 'Depok',
                    'province' => 'Jawa Utara',
                    'country' => 'Indonesia',
                    'postal_code' => '16515',
                    'contact_id' => $address->contact_id
                ]
            ]);
    }
    public function testUpdateFailed()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id, [
            'street' => '',
            'city' => '',
            'province' => '',
            'country' => '',
            'postal_code' => '',
            'contact_id' => ''
        ], [
            'Authorization' => 'test'
        ])->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                "errors" => [
                    "contact_id" => [
                        "The contact id field is required."
                    ],
                    "street" => [
                        "The street field is required."
                    ],
                    "city" => [
                        "The city field is required."
                    ],
                    "province" => [
                        "The province field is required."
                    ],
                    "country" => [
                        "The country field is required."
                    ],
                    "postal_code" => [
                        "The postal code field is required."
                    ]
                ]
            ]);
    }
    public function testUpdateContactIdNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id .= 'me'. '/addresses/' . $address->id, [
            'street' => 'Jl. Meruyung Raya',
            'city' => 'Depok',
            'province' => 'Jawa Utara',
            'country' => 'Indonesia',
            'postal_code' => '16515',
            'contact_id' => $address->contact_id
        ], [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'message' => 'Data not found.'
            ]);
    }
    public function testUpdateAddressIdNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . $address->contact_id . '/addresses/' . $address->id .= 'me', [
            'street' => 'Jl. Meruyung Raya',
            'city' => 'Depok',
            'province' => 'Jawa Utara',
            'country' => 'Indonesia',
            'postal_code' => '16515',
            'contact_id' => $address->contact_id
        ], [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'message' => 'Data not found.'
            ]);
    }
    public function testUpdateNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class, AddressSeeder::class]);

        $address = Address::query()->limit(1)->first();

        $this->put('/api/contacts/' . '' . '/addresses/' . '', [
            'street' => 'Jl. Meruyung Raya',
            'city' => 'Depok',
            'province' => 'Jawa Utara',
            'country' => 'Indonesia',
            'postal_code' => '16515',
            'contact_id' => $address->contact_id
        ], [
            'Authorization' => 'test'
        ])->assertStatus(404)
            ->assertJson([
                'message' => 'Data not found.'
            ]);
    }
}
