<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super = Role::firstOrCreate(
            ["name" => "super"]
        );

        $user = User::firstOrNew();
        $user->name = "Admin";
        $user->email = "admin@portal.in";
        $user->password = Hash::make('admin');
        $user->save();

        $user->assignRole($super);
    }
}

