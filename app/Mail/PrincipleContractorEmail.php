<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PrincipleContractorEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $key;
    public $project;
    public $link;

    public function __construct($key, $project)
    {
        $this->key = $key;
        $this->project = $project;
        $this->link = env('APP_URL').$this->key.'/vtrams';

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.principle_contractor')
            ->subject('VTRAMS for Review');
    }
}
