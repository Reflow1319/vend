<?php

namespace App\Http\Controllers;

use App\Card;
use App\Event;
use App\Http\Requests\EventRequest;
use App\Project;
use App\User;
use Sabre\VObject\Component\VCalendar;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:editor', ['only' => ['store', 'update', 'destroy']]);
    }

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

        return response($vcalendar->serialize(), 200, [
            'Content-type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename=calendar.ics',
        ]);
    }

    public function store(EventRequest $eventRequest)
    {
        return Event::create($eventRequest->all());
    }

    public function update(Event $event, EventRequest $eventRequest)
    {
        $event->update($eventRequest->all());
        return $event;
    }

    private function getEvents($hash = null)
    {
        $user = null;
        if ($hash) {
            $user = User::whereEventUrl($hash)->first();
            if (!$user) {
                throw new NotFoundHttpException();
            }
        }

        $events = Event::orderBy('start')
            ->where(function ($query) use ($user) {
                if ($user) {
                    $query->where('event_url', $user->event_url);
                }
            })
            ->get();

        $events = $events->map(function ($event) {
            $event->type = 'event';
            return $event;
        });

        $projects = Project::where('due_date', '!=', null)->get();

        $cards = Card::where('due_date', '!=', null)->get();

        $projectEvents = $projects->map(function ($project) {
            return [
                'id' => $project->id,
                'title' => $project->title,
                'start' => $project->due_date,
                'location' => null,
                'end' => null,
                'type' => 'project',
            ];
        });

        $cardEvents = $cards->map(function ($card) {
            return [
                'id' => $card->id,
                'title' => $card->title,
                'start' => $card->due_date,
                'location' => null,
                'end' => null,
                'type' => 'card',
                'meta' => [
                    'project_id' => $card->project_id
                ]
            ];
        });

        return $projectEvents->merge($events)->merge($cardEvents);
    }

    public function destroy(Event $event)
    {
        $event->delete();
    }
}