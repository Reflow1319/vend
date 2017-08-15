<?php

use App\Card;
use App\Event;
use App\Project;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\CreatesUser;

class EventTest extends \Tests\TestCase
{
    use DatabaseMigrations, CreatesUser;

    public function testICal()
    {
        $userEditor = $this->createEditor();

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

        $this->actingAs($userEditor)
            ->json('GET', 'calendar/'.$userEditor->event_url.'/calendar.ics')
            ->assertHeader('Content-type', 'text/calendar; charset=utf-8')
            ->assertHeader('Content-Disposition', 'inline; filename=calendar.ics')
            ->assertStatus(200);
    }
}
