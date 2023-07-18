<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = config('perms');

        foreach($roles as $perms){

            $split = explode(":", $perms);
            
            if(count($split) > 2){
                $role = $split[0];
            }
            else{
                $role = 'common';
            }

            $this->command->info($perms);

            $permission = Permission::firstOrCreate(
                ["name" => $perms]
            );

            $permissions[] = $permission->id;
            $permission_split[$role][] = $permission->id;
        }

        $super = Role::firstOrCreate(
            ["name" => "super"]
        );

        $super->syncPermissions($permissions);

        foreach(array_keys($permission_split) as $item){
            if($item != "common"){

                $role = Role::firstOrCreate(
                    ["name" => $item], ["type"=> $item]
                );

                $item_perms = array_merge($permission_split[$item], $permission_split['common']);

                $role->syncPermissions($item_perms);

            }
        }
    }
}
