<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        Schema::defaultStringLength(191);

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Sidebar menu items
            $users = [
                'text' => 'Accounts',
                'url' => 'users',
                'icon' => 'users',
            ];
            $products = [
                'text' => 'Customers',
                'url'  => 'products',
                'icon' => 'list-ul',
            ];
            $tags = [
                'text' => 'Tags',
                'url'  => 'tags',
                'icon' => 'tags',
            ];
            $settings = [
                'text' => 'Settings',
                'url'  => 'settings',
                'icon' => 'cog',
            ];

            // Check if authrized user is super admin.
            if (Auth::user()->hasRole('super admin')) {
                // add menu items
                $event->menu->add(
                    $users,
                    $products,
                    $tags,
                    $settings
                );
            } else {
                // add menu items
                $event->menu->add(
                    $products,
                    $settings
                );
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}