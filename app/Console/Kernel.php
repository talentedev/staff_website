<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Carbon\Carbon;
use App\Mail\StatusReminder;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $current = Carbon::now()->toDateString();
            $emails = DB::table('email_queue')->where('send_date', $current)->get();
            foreach ($emails as $key => $value) {
                $email_data = array(
                    'name' => $value->email
                );
                if ($value->send_order == 1) {
                    Mail::to($value->email)->send(new StatusReminder($email_data, 'ship_reminder1'));
                    DB::table('email_queue')->where('id', $value->id)->delete();
                } else if($value->send_order == 2) {
                    Mail::to($value->email)->send(new StatusReminder($email_data, 'ship_reminder2'));
                    DB::table('email_queue')->where('id', $value->id)->delete();
                }
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
