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
                'text' => 'Staff',
                'url' => 'staff',
                'icon' => 'users',
            ];
            $products = [
                'text' => 'Customers',
                'url'  => 'customers',
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
            $email = [
                'text' => 'Email Settings',
                'url'  => 'emails',
                'icon' => 'envelope',
            ];

            // Check if authrized user is super admin.
            if (Auth::user()->hasRole('super admin')) {
                // add menu items
                $event->menu->add(
                    $users,
                    $products,
                    // $tags,
                    $settings,
                    $email
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