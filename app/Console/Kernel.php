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
            // $currentStart = Carbon::now()->format('Y-m-d 00:00:00');//->toDateTimeString();
            // $currentEnd = Carbon::now()->format('Y-m-d 23:59:59');//->toDateTimeString();
            // $emails = DB::table('email_queue')->whereBetween('send_date', [$currentStart,$currentEnd])->get();
            $emails = DB::table('email_queue')->whereDate('send_date', '<', Carbon::now())->get();
            
            foreach ($emails as $key => $value) {
                $product = DB::table('products')->find($value->product_id);
                $email_data = array(
                    'name' => $product->first_name,
                    'link' => 'https://id.pheramor.com/status.php?pheramor_id=' . $product->pheramor_id,
                    'to' => $product->sales_email
                );
                // return $this->respond($product);
                if ($product->swab_returned_date == null) {
                    if ($value->send_order == 1) {
                        $this->sendMail($product->sales_email, $product->account_email, $email_data, 'ship_reminder1');
                        DB::table('email_queue')->where('id', $value->id)->delete();
                    } else {
                        $this->sendMail($product->sales_email, $product->account_email, $email_data, 'ship_reminder2');
                        DB::table('email_queue')->where('id', $value->id)->delete();
                    }
                } else {
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

    // Send mail
    public function sendMail($to, $cc, $data, $type) {

        if ($cc == null || $cc == '') {
            Mail::to($to)->queue(new StatusReminder($data, $type));
        } else {
            Mail::to($to)
                ->cc($cc)
                ->queue(new StatusReminder($data, $type));
        }

    }
}
