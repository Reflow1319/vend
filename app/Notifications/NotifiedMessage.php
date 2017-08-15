<?php

namespace App\Notifications;

use App\Card;
use App\Comment;
use App\Project;
use App\Topic;
use Illuminate\Database\Eloquent\Model;

class NotifiedMessage implements NotifiedResource
{
    /**
     * @var Card
     */
    protected $card;

    /**
     * @var Project
     */
    protected $topic;

    /**
     * @var Comment
     */
    private $message;

    /**
     * NotifiedCard constructor.
     *
     * @param Model $message
     * @param Topic $topic
     */
    public function __construct(Model $message, Topic $topic)
    {
        $this->topic = $topic;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->message->id;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'message_id'  => $this->message->id,
            'topic_id'    => $this->topic->id,
            'topic_title' => $this->topic->title,
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
        return 'message';
    }
}
