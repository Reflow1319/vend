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
     * @var bool
     */
    private $isNew;

    /**
     * NotifiedCard constructor.
     *
     * @param Model   $previous
     * @param Card    $card
     * @param Project $project
     * @param bool    $isNew
     */
    public function __construct(Model $previous, Card $card, Project $project, $isNew = false)
    {
        $this->card = $card;
        $this->project = $project;
        $this->previous = $previous;
        $this->isNew = $isNew;
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
        $modelDiff = new ModelDiff($this->previous, $this->card, [
            'title',
            'description',
            'assigned_to',
            'due_date',
        ]);

        $modelDiff->map('assigned_to', User::class, 'name');

        return [
            'title'         => $this->card->title,
            'project_id'    => $this->project->id,
            'project_title' => $this->project->title,
            'changes'       => $this->isNew ? null : $modelDiff->get(),
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
