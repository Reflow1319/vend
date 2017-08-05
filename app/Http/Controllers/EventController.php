<?php

namespace App\Http\Controllers;

use App\Card;
use App\Event;
use App\Http\Requests\EventRequest;
use App\Notifications\NotifiedEvent;
use App\Notifications\Notify;
use App\Project;
use App\User;
use Sabre\VObject\Component\VCalendar;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends Controller
{
    /**
     * EventController constructor.
     */
    public function __construct()
    {
        $this->middleware(
            'role:editor',
            ['only' => ['store', 'update', 'destroy']]
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = $this->getEvents();

        return response()->make($events);
    }

    public function ical($hash)
    {
        $events = $this->getEvents($hash);

        $vcalendar = new VCalendar();
        foreach ($events as $event) {
            $e = [
                'SUMMARY' => $event->title,
                'DTSTART' => new \DateTime($event->start),
            ];

            if ($event->end) {
                $e['DTEND'] = new \DateTime($event->end);
            }

            $vcalendar->add('VEVENT', $e);
        }

        return response(
            $vcalendar->serialize(),
            200,
            [
                'Content-type'        => 'text/calendar; charset=utf-8',
                'Content-Disposition' => 'inline; filename=calendar.ics',
            ]
        );
    }

    /**
     * @param EventRequest $eventRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $eventRequest)
    {
        $event = Event::create($eventRequest->all());
        $this->createNotification($event);

        return response()->make($event);
    }

    /**
     * @param Event        $event
     * @param EventRequest $eventRequest
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Event $event, EventRequest $eventRequest)
    {
        $event->update($eventRequest->all());
        $this->createNotification($event);

        return response()->make($event);
    }

    /**
     * @param Event $event
     */
    private function createNotification(Event $event)
    {
        notify(new NotifiedEvent($event))
            ->to(User::get())
            ->create();
    }

    /**
     * @param null $hash
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static|static[]
     */
    private function getEvents($hash = null)
    {
        $user = null;
        if ($hash) {
            $user = User::whereEventUrl($hash)->first();
            if ( ! $user) {
                throw new NotFoundHttpException();
            }
        }

        // Get from db
        $events = Event::orderBy('start')
            ->where(function ($query) use ($user) {
                if ($user) {
                    $query->where('event_url', $user->event_url);
                }
            })->get();

        // Add type to events
        $events = collect($events->map(function ($event) {
            $event->type = 'event';

            return $event;
        }));

        $events = $events->merge(Project::events());
        $events = $events->merge(Card::events());

        return $events;
    }

    /**
     * @param Event $event
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        return response()->make($event);
    }

    /**
     * @param Event $event
     */
    public function destroy(Event $event)
    {
        $event->delete();
    }
}