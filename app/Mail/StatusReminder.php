<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The mail data
     *
     * @var data
     */
    public $data;

    /**
     * The mail type
     *
     * @var type
     */
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->type) {
            case 'ship_reminder1':
                return $this->subject("We Haven't Received Your Pheramor Kit Yet =(")->view('emails.ship.reminder1');
                break;
            case 'ship_reminder2':
                return $this->subject("The swabs at the lab miss their swab buddies. Send them back!")->view('emails.ship.reminder2');
                break;
            default:
                # code...
                break;
        }
    }
}
