<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('username', 'test')->first();

        Contact::create([
            "first_name" => "Bima Arya",
            "last_name" => "Wicaksana",
            "email" => "wicaksanabimaarya@gmail.com",
            "phone" => "089638307725",
            "user_id" => $user->id
        ]);
    }
}
