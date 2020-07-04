<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';
    protected $guarded = [];

    public function participants()
    {
        return $this->belongsToMany('App\Participant', 'participant_events', 'event_id', 'participant_id');
    }

    public function hasParticipant(Participant $participant)
    {
        return $this->participants()->find($participant->id);
    }

    public function addParticipants(Collection $participants)
    {
        $participants->each(function ($participant, $key) {
            $this->participants()->save($participant);
        });
    }
}
