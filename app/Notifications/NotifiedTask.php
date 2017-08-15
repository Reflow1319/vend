<?php

namespace App\Notifications;

use App\Card;
use App\Helpers\ModelDiff;
use App\Project;
use App\Task;
use Illuminate\Database\Eloquent\Model;

class NotifiedTask implements NotifiedResource
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
     * @var Task
     */
    private $task;

    /**
     * @var bool
     */
    private $isNew;

    /**
     * @var Model|Task
     */
    private $previous;

    /**
     * NotifiedCard constructor.
     *
     * @param Task|Model $previous
     * @param Task|Model $task
     * @param Card       $card
     * @param Project    $project
     * @param bool       $isNew
     */
    public function __construct(
        Model $previous,
        Model $task,
        Card $card,
        Project $project,
        $isNew
    ) {
        $this->card = $card;
        $this->previous = $previous;
        $this->project = $project;
        $this->task = $task;
        $this->isNew = $isNew;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->task->id;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $modelDiff = new ModelDiff($this->previous, $this->task, [
            'is_completed',
        ]);

        return [
            'card_id'       => $this->card->id,
            'card_title'    => $this->card->title,
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
        return $this->isNew
            ? 'created'
            : 'updated';
    }

    /**
     * @return mixed
     */
    public function getRelatedType()
    {
        return 'task';
    }
}
