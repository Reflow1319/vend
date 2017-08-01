<?php

namespace App\Notifications;

use App\Card;
use App\Helpers\ModelDiff;
use App\Project;
use App\User;
use Illuminate\Database\Eloquent\Model;

class NotifiedCard implements NotifiedResource
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
     * @var Card
     */
    private $previous;

    /**
     * NotifiedCard constructor.
     *
     * @param Model   $previous
     * @param Card    $card
     * @param Project $project
     */
    public function __construct(Model $previous, Card $card, Project $project)
    {
        $this->card     = $card;
        $this->project  = $project;
        $this->previous = $previous;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->card->id;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if($this->previous->exists) {
            $modelDiff = new ModelDiff($this->previous, $this->card, [
                'title',
                'description',
                'assigned_to',
                'due_date',
            ]);

            $modelDiff->map('assigned_to', User::class, 'name');
        }

        return [
            'title'         => $this->card->title,
            'project_id'    => $this->project->id,
            'project_title' => $this->project->title,
            'changes'       => $this->previous->exists ? $modelDiff->get(): [],
        ];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->card->exists
            ? 'updated'
            : 'created';
    }

    /**
     * @return string
     */
    public function getRelatedType()
    {
        return 'card';
    }
}