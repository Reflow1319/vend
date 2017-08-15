<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests\EventRequest;
use App\Notifications\NotifiedEvent;
use App\User;
use Sabre\VObject\Component\VCalendar;

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
        $events = Event::getAllEvents();

        return response()->make($events);
    }

    public function ical($hash)
    {
        $events = Event::getAllEvents($hash);

        $vcalendar = new VCalendar();
        foreach ($events as $event) {
            $e = [
                'SUMMARY' => $event['title'],
                'DTSTART' => new \DateTime($event['start']),
            ];

            if ($event['end']) {
                $e['DTEND'] = new \DateTime($event['end']);
            }

            $vcalendar->add('VEVENT', $e);
        }

        $headers = [
            'Content-type'        => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename=calendar.ics',
        ];

        return response($vcalendar->serialize(), 200, $headers);
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
