<?php

namespace Database\Seeders;

use App\Models\Admins;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $pass = Hash::make("!@#\$Jk1234");
        $admin = Admins::create([
            'firstname' => "Japheth",
            'lastname' => "Kiprotich",
            'email' => "itsjkiprotich@gmail.com",
            'password' =>  $pass,
            'phone' => "254724743788",
            'usertype' => "Super Admin",
            'status' => true
        ]);

        if ($admin) {
            $user = User::create([
                'name' => "Kiprotich Japheth",
                'password' => $pass,
                'email' => "itsjkiprotich@gmail.com",
            ]);
            Branch::create([
                'name' => "A",
                'description' => "A"
            ]);
        }
    }
}
