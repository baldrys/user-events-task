<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Event;
use App\Participant;
use App\Http\Resources\Event as EventResource;
use App\Http\Resources\Participant as ParticipantResource;
use App\Http\Requests\ParticipantAddRequest;
use App\Http\Requests\ParticipantUpdateRequest;
use App\Managers\EventManager;

class EventController extends Controller
{
    /**
     * Получить всех участников.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function getParticipants(Event $event)
    {
        return new EventResource($event);
    }

    /**
     * Добавить участника.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function addParticipant(ParticipantAddRequest $request, Event $event, EventManager $manager)
    {
        $participant = $manager->addParticipant($request, $event);
        return new ParticipantResource($participant);
    }

    /**
     * Изменить участника.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function updateParticipant(ParticipantUpdateRequest $request, Event $event, Participant $participant, EventManager $manager)
    {
        $participant = $manager->updateParticipant($request, $event, $participant);
        return new ParticipantResource($participant);
    }

    /**
     * Удалить участника.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function deleteParticipant(Event $event, Participant $participant, EventManager $manager)
    {
        $manager->deleteParticipant($event, $participant);
        return response()->json([
            "success" => true
        ]);
    }
}
