<?php

namespace Tests\Unit;

use App\Participant;
use App\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $events;

    const NUM_OF_EVENTS = 5;
    const NUM_OF_PARTICIPANTS = 5;

    protected function setUp(): void
    {
        parent::setUp();
        $this->events = factory(Event::class, self::NUM_OF_EVENTS)->create();
        $this->user = factory(User::class)->create();
    }

    private function getParticipant()
    {
        return [
            'first_name' => 'Pavell',
            'last_name' => 'Redkva',
            'email' => 'redkva@gmail.com',
        ];
    }

    private function getParticipantIncorrect()
    {
        return [
            'first_name' => 'Pavel',
            'last_name' => 'Redkva',
        ];
    }

    // ---  Get event participants ---

    /**
     * @test
     * @return void
     */
    public function getParticipants_UserAuthOk_Success()
    {
        $this->actingAs($this->user, 'api');
        $participants =  factory(Participant::class, self::NUM_OF_PARTICIPANTS)->create();
        $event = $this->events->first();
        $event->addParticipants($participants);
        $response = $this->json("GET", "/api/events/{$event->id}");
        $response->assertStatus(Response::HTTP_OK)->assertJsonCount(self::NUM_OF_PARTICIPANTS, 'data.participants');
    }

    /**
     * @test
     * @return void
     */
    public function getParticipants_UserNotAuth_Unauthorize()
    {
        $event = $this->events->first();
        $response = $this->json("GET", "/api/events/{$event->id}");
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    // --- / Get Event participants ---

    // --- Add Event participant ---

    /**
     * @test
     * @return void
     */
    public function addParticipant_UserAuthOkDataCorrect_Success()
    {
        $this->actingAs($this->user, 'api');
        $event = $this->events->first();
        $response = $this->json(
            "POST",
            "/api/events/{$event->id}",
            $this->getParticipant()
        );
        $numOfParticipants = $event->participants->count();
        $this->assertEquals($numOfParticipants, 1);
        $response->assertStatus(Response::HTTP_CREATED)->assertJsonFragment($this->getParticipant());
    }

    /**
     * @test
     * @return void
     */
    public function addParticipant_UserNotAuth_Unauthorize()
    {
        $event = $this->events->first();
        $response = $this->json(
            "POST",
            "/api/events/{$event->id}",
            $this->getParticipant()
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @return void
     */
    public function addParticipant_UserAuthOkDataNotCorrect_Unprocessable()
    {
        $this->actingAs($this->user, 'api');
        $event = $this->events->first();
        $response = $this->json(
            "POST",
            "/api/events/{$event->id}",
            $this->getParticipantIncorrect()
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * @return void
     */
    public function addParticipant_UserAuthOkEmailAlreadyTaken_Unprocessable()
    {
        $this->actingAs($this->user, 'api');
        $event = $this->events->first();
        $participant =  factory(Participant::class)->create();
        $event->participants()->save($participant);
        $participantData = $this->getParticipantIncorrect();
        $participantData['email'] = $participant->email;
        $response = $this->json(
            "POST",
            "/api/events/{$event->id}",
            $participantData
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // --- / Add Event participant ---

    // --- Update Event participant ---

    /**
     * @test
     * @return void
     */
    public function updateParticipant_UserAuthOkDataCorrect_Sucess()
    {
        $this->actingAs($this->user, 'api');
        $event = $this->events->first();
        $participant =  factory(Participant::class)->create();
        $event->participants()->save($participant);

        $response = $this->json(
            "PATCH",
            "/api/events/{$event->id}/participants/{$participant->id}",
            $this->getParticipant()
        );
        $response->assertStatus(Response::HTTP_OK)->assertJsonFragment($this->getParticipant());
    }

    /**
     * @test
     * @return void
     */
    public function updateParticipant_UserNotAuth_Unauthorize()
    {
        $event = $this->events->first();
        $participant =  factory(Participant::class)->create();

        $response = $this->json(
            "PATCH",
            "/api/events/{$event->id}/participants/{$participant->id}",
            $this->getParticipant()
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @return void
     */
    public function updateParticipant_UserAuthOkParticipantNotInEvent_BadRequest()
    {
        $this->actingAs($this->user, 'api');
        $event = $this->events->first();
        $participant =  factory(Participant::class)->create();

        $response = $this->json(
            "PATCH",
            "/api/events/{$event->id}/participants/{$participant->id}",
            $this->getParticipant()
        );
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     * @return void
     */
    public function updateParticipant_UserAuthOkDataIncorrect_BadRequest()
    {
        $this->actingAs($this->user, 'api');
        $event = $this->events->first();
        $participant =  factory(Participant::class)->create();
        $event->participants()->save($participant);
        $response = $this->json(
            "PATCH",
            "/api/events/{$event->id}/participants/{$participant->id}",
            $this->getParticipantIncorrect()
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // --- / Update Event participant ---

    // --- Delete Event participant ---

    /**
     * @test
     * @return void
     */
    public function deleteParticipant_UserAuthOkParticipantExists_Sucess()
    {
        $this->actingAs($this->user, 'api');
        $event = $this->events->first();
        $participant =  factory(Participant::class)->create();
        $event->participants()->save($participant);
        $response = $this->json(
            "DELETE",
            "/api/events/{$event->id}/participants/{$participant->id}"
        );
        $numOfParticipants = $event->participants->count();
        $this->assertEquals($numOfParticipants, 0);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     * @return void
     */
    public function deleteParticipant_UserNotAuth_Unauthorize()
    {
        $event = $this->events->first();
        $participant =  factory(Participant::class)->create();
        $event->participants()->save($participant);
        $response = $this->json(
            "DELETE",
            "/api/events/{$event->id}/participants/{$participant->id}"
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     * @return void
     */
    public function deleteParticipant_UserAuthOkParticipantNotExists_BadRequest()
    {
        $this->actingAs($this->user, 'api');
        $event = $this->events->first();
        $participant =  factory(Participant::class)->create();
        $response = $this->json(
            "DELETE",
            "/api/events/{$event->id}/participants/{$participant->id}"
        );
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    // --- / Delete Event participant ---

}
