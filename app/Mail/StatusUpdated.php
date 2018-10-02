<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusUpdated extends Mailable
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
            case 'ship_update':
                return $this->subject('Your Pheramor Kit is on the way!')->view('emails.ship.updated');
                break;
            case 'sales_update':
                return $this->subject('Thank you for purchasing a Pheramor kit!')->view('emails.sales.updated');
                break;
            case 'account_update':
                return $this->subject('Thanks for connecting your Pheramor Kit')->view('emails.account-connected.updated');
                break;
            case 'swab_update':
                return $this->subject('Your Pheramor Kit was Successfully Returned')->view('emails.swab-returned.updated');
                break;
            case 'sequenced_update':
                return $this->subject('Your genetic data has been uploaded - Check it out!')->view('emails.sequenced.updated');
                break;
            default:
                # code...
                break;
        }
    }
}
