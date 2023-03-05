<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
	    \App\Models\User::factory(1)->create(
		    [
			    'name' => 'John Doe',
			    'email' => 'info@info.com',
			    'password' => bcrypt(123123),
			    'email_verified_at' => null,
		    ]
	    );
    }
}
