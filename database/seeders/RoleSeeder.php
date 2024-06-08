<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = Role::create(["name" => "super admin"]);
        $admin = Role::create(["name" => "admin"]);
        $superVisor = Role::create(["name" => "supervisor"]);
        $manager = Role::create(["name" => "manager"]);
        $agent = Role::create(["name" => "agent"]);
        $owner = Role::create(["name" => "owner"]);
        $careTaker = Role::create(["name" => "care taker"]);

        //user//
        Permission::create(['name' => 'view user'])->syncRoles(['admin']);
        Permission::create(['name' => 'add user'])->syncRoles(['admin']);
        Permission::create(['name' => 'edit user'])->syncRoles(['admin']);
        Permission::create(['name' => 'delete user'])->syncRoles(['admin']);
        //end user//
        //permission//
        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'add permission']);
        Permission::create(['name' => 'edit permission']);
        Permission::create(['name' => 'delete permission']);
        //end permission//
        //role//
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'add role']);
        Permission::create(['name' => 'edit role']);
        Permission::create(['name' => 'delete role']);
        //end role//
        //owner//
        Permission::create(['name' => 'view owner'])->syncRoles(['admin']);
        Permission::create(['name' => 'add owner'])->syncRoles(['admin']);
        Permission::create(['name' => 'edit owner'])->syncRoles(['admin']);
        Permission::create(['name' => 'delete owner'])->syncRoles(['admin']);
        Permission::create(['name' => 'permanent delete owner'])->syncRoles(['admin']);
        Permission::create(['name' => 'restore owner'])->syncRoles(['admin']);
        //end owner//
        //role//
        Permission::create(['name' => 'view staycation'])->syncRoles(['admin']);
        Permission::create(['name' => 'add staycation'])->syncRoles(['admin']);
        Permission::create(['name' => 'edit staycation'])->syncRoles(['admin']);
        Permission::create(['name' => 'delete staycation'])->syncRoles(['admin']);
        Permission::create(['name' => 'permanent delete staycation'])->syncRoles(['admin']);
        Permission::create(['name' => 'restore staycation'])->syncRoles(['admin']);
        Permission::create(['name' => 'assign staycation'])->syncRoles(['supervisor','manager','admin']);
        Permission::create(['name' => 'remove assigned staycation'])->syncRoles(['supervisor','manager','admin']);
        Permission::create(['name' => 'view assigned staycation'])->syncRoles(['supervisor','manager','agent','admin']);
        //end role//

        //staycation package//
        Permission::create(['name' => 'view staycation package'])->syncRoles(['admin']);
        Permission::create(['name' => 'add staycation package'])->syncRoles(['admin']);
        Permission::create(['name' => 'edit staycation package'])->syncRoles(['admin']);
        Permission::create(['name' => 'delete staycation package'])->syncRoles(['admin']);
        Permission::create(['name' => 'permanent delete staycation package'])->syncRoles(['admin']);
        Permission::create(['name' => 'restore staycation package'])->syncRoles(['admin']);
        //end staycation package//

        //appointment//
        Permission::create(['name' => 'view booking'])->syncRoles(['supervisor','manager','admin','agent']);
        Permission::create(['name' => 'add booking'])->syncRoles(['supervisor','manager','admin','agent']);
        Permission::create(['name' => 'edit booking'])->syncRoles(['supervisor','manager','admin','agent']);
        Permission::create(['name' => 'delete booking'])->syncRoles(['supervisor','manager','admin','agent']);
        Permission::create(['name' => 'permanent delete booking'])->syncRoles(['admin']);
        Permission::create(['name' => 'restore booking'])->syncRoles(['admin']);
        //end appointment//

        //customer//
        Permission::create(['name' => 'view customer'])->syncRoles(['supervisor','manager','admin','agent','owner']);
        Permission::create(['name' => 'add customer'])->syncRoles(['supervisor','manager','admin','agent','owner']);
        Permission::create(['name' => 'edit customer'])->syncRoles(['supervisor','manager','admin','agent','owner']);
        Permission::create(['name' => 'delete customer'])->syncRoles(['supervisor','manager','admin','agent','owner']);
        Permission::create(['name' => 'permanent delete customer'])->syncRoles(['admin']);
        Permission::create(['name' => 'restore customer'])->syncRoles(['admin']);
        //end customer//
    }
}
