<?php

namespace App\Notifications;

use App\Event;
use Illuminate\Database\Eloquent\Model;

class NotifiedEvent implements NotifiedResource
{
    /**
     * @var Event
     */
    private $event;

    /**
     * NotifiedCard constructor.
     *
     * @param Event|Model $event
     */
    public function __construct(Model $event)
    {
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->event->id;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'event_id' => $this->event->id,
            'title'    => $this->event->title,
            'start'    => $this->event->start,
            'location' => $this->event->location,
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'created';
    }

    /**
     * @return mixed
     */
    public function getRelatedType()
    {
        return 'event';
    }
}
