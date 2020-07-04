<?php

namespace App\Managers;

use Illuminate\Http\Request;
use App\Event;
use App\Participant;
use App\Http\Resources\Event as EventResource;
use App\Http\Resources\Participant as ParticipantResource;
use App\Http\Requests\ParticipantAddRequest;
use App\Http\Requests\ParticipantUpdateRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\ParticipantMail;

class EventManager
{

    public function addParticipant(ParticipantAddRequest $request, Event $event)
    {
        $participant = new Participant($request->all());
        if ($event->participants()->save($participant))
            Mail::to($participant)->queue(new ParticipantMail($participant, $event));
        return $participant;
    }

    public function updateParticipant(ParticipantUpdateRequest $request, Event $event, Participant $participant)
    {
        if (!$event->hasParticipant($participant))
            throw new HttpException(Response::HTTP_BAD_REQUEST, "There is no participant with id $participant->id in the event!");
        $participant->update($request->all());
        return $participant;
    }

    public function deleteParticipant(Event $event, Participant $participant)
    {
        if (!$event->hasParticipant($participant))
            throw new HttpException(Response::HTTP_BAD_REQUEST, "There is no participant with id $participant->id in the event!");
        $participant->delete();
    }
}
