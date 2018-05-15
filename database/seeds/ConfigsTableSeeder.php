<?php

use Illuminate\Database\Seeder;

class ConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // AgileCRM configurations
        $config = new App\Config;
        $config->agile_domain = "likeswiperight";
        $config->agile_email = "jin@pheramor.com";
        $config->agile_key = "upns4k6ajqtkm4ovkjb5hjjj9a";
        $config->save();
    }
}
