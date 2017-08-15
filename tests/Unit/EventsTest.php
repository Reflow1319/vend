<?php

use App\Card;
use App\Event;
use App\Project;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Tests\CreatesProject;
use Tests\TestCase;

class EventsTest extends TestCase
{
    use DatabaseMigrations, CreatesProject;

    public function testCardEvents()
    {
        $date = Carbon::now()->format('Y-m-d');

        factory(Card::class)->create([
            'title'      => 'Test card',
            'due_date'   => $date,
            'column_id'  => 1,
            'project_id' => 1,
        ]);

        $events = Card::events();

        $data = [
            'id'       => 1,
            'title'    => 'Test card',
            'start'    => $date,
            'location' => null,
            'end'      => null,
            'type'     => 'card',
            'meta'     => [
                'project_id' => 1,
            ],
        ];

        $this->assertEquals($events->get(0), $data);
    }

    public function testProjectEvents()
    {
        $date = Carbon::now()->format('Y-m-d');

        factory(Project::class)->create([
            'title'    => 'Test project',
            'due_date' => $date,
        ]);

        $events = Project::events();

        $data = [
            'id'       => 1,
            'title'    => 'Test project',
            'start'    => $date,
            'location' => null,
            'end'      => null,
            'type'     => 'project',
        ];

        $this->assertEquals($events->get(0), $data);
    }

    public function testAllEvents()
    {
        $date = Carbon::now()->format('Y-m-d');

        factory(Project::class)->create([
            'title'    => 'Test project',
            'due_date' => $date,
        ]);

        factory(Card::class)->create([
            'title'      => 'Test card',
            'due_date'   => $date,
            'column_id'  => 1,
            'project_id' => 1,
        ]);

        factory(Event::class)->create([
            'title' => 'Test event',
            'start' => $date,
            'end'   => null,
        ]);

        $events = Event::getAllEvents();

        $this->assertTrue($events instanceof Collection);
    }
}
