<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Super Admin
        $super = new App\User;
        $super->name = "Super Admin";
        $super->email = "super@gmail.com";
        $super->tag = "Super Admin";
        $super->password = \Illuminate\Support\Facades\Hash::make("123456");
        $super->save();

        $super->assignRole('super admin');

        // Admin User
        $admin = new App\User;
        $admin->name = "Admin User";
        $admin->email = "admin@gmail.com";
        $admin->tag = "Admin";
        $admin->password = \Illuminate\Support\Facades\Hash::make("123456");
        $admin->save();

        $admin->assignRole('admin');

        // Street Team
        $team = new App\User;
        $team->name = "Street Team";
        $team->email = "team@gmail.com";
        $team->source = "staff123457";
        $team->tag = "Street";
        $team->password = \Illuminate\Support\Facades\Hash::make("123456");
        $team->save();

        $team->assignRole('street team');

        // Staff
        $staff = new App\User;
        $staff->name = "Staff";
        $staff->email = "staff@gmail.com";
        $staff->tag = "Staff";
        $staff->password = \Illuminate\Support\Facades\Hash::make("123456");
        $staff->save();

        $staff->assignRole('staff');
    }
}