<?php

namespace App\Mail;

use App\Participant;
use App\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParticipantMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Participant instance.
     *
     * @var Participant
     */
    protected $participant;

    /**
     * The Event instance.
     *
     * @var Event
     */
    protected $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event)
    {
        $this->participant = $participant;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('participantEmail')->with([
            'participant' => $this->participant,
            'event' => $this->event,
        ]);;
    }
}
