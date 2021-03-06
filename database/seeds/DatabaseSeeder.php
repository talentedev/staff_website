<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesTableSeeder::class,
            PermissionTableSeeder::class,
            UsersTableSeeder::class,
            TagsTableSeeder::class,
            ConfigsTableSeeder::class
        ]);
    }
}
