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
        $user->name = "Sidharth";
        $user->email = "sidharth@startupmission.in";
        $user->password = Hash::make('sidharth');
        $user->save();

        $user->assignRole($super);
    }
}

