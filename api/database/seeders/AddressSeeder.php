<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $contact = Contact::query()->limit(1)->first();

        Address::create([
            'street' => 'Jl. Meruyung Raya',
            'city' => 'Depok',
            'province' => 'Jawa Barat',
            'country' => 'Indonesia',
            'postal_code' => '16515',
            'contact_id' => $contact->id
        ]);
    }
}
