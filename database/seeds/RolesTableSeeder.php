<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Super Admin
        $super = new Role();
        $super->name  = 'super admin';
        $super->guard_name = 'web';
        $super->save();

        // Admin
        $admin = new Role();
        $admin->name  = 'admin';
        $admin->guard_name = 'web';
        $admin->save();

        // Street Team
        $team = new Role();
        $team->name  = 'street team';
        $team->guard_name = 'web';
        $team->save();

        // Staff
        $staff = new Role();
        $staff->name  = 'staff';
        $staff->guard_name = 'web';
        $staff->save();
    }
}