<?php

namespace App\Notifications;

use App\Card;
use App\Comment;
use App\Project;
use Illuminate\Database\Eloquent\Model;

class NotifiedComment implements NotifiedResource
{
    /**
     * @var Card
     */
    protected $card;

    /**
     * @var Project
     */
    protected $project;

    /**
     * @var Comment
     */
    private $comment;

    /**
     * NotifiedCard constructor.
     *
     * @param Comment|Model $comment
     * @param Card          $card
     * @param Project       $project
     */
    public function __construct(Model $comment, Card $card, Project $project)
    {
        $this->card = $card;
        $this->project = $project;
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->comment->id;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'card_id'       => $this->card->id,
            'card_title'    => $this->card->title,
            'project_id'    => $this->project->id,
            'project_title' => $this->project->title,
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
        return 'comment';
    }
}
