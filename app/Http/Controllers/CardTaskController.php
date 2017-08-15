<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Requests\TaskRequest;
use App\Notifications\NotifiedTask;
use App\Project;
use App\Task;

class CardTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:editor');
        $this->middleware('member:project');
    }

    /**
     * @param Project     $project
     * @param Card        $card
     * @param TaskRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @internal param Account $account
     */
    public function store(Project $project, Card $card, TaskRequest $request)
    {
        $task = $card->tasks()->save(new Task($request->all()));

        notify(new NotifiedTask(new Task(), $task, $card, $project, true))
            ->to($project->users)
            ->create();

        return response()->make($task);
    }

    /**
     * @param Project     $project
     * @param Card        $card
     * @param Task        $task
     * @param TaskRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @internal param Account $account
     */
    public function update(
        Project $project,
        Card $card,
        Task $task,
        TaskRequest $request
    ) {
        $previous = $task->replicate();

        $task->update($request->all());

        notify(new NotifiedTask($previous, $task, $card, $project, false))
            ->to($project->users)
            ->create();

        return response()->make($task);
    }

    /**
     * @param Project $project
     * @param Card    $card
     * @param Task    $task
     *
     * @internal param Account $account
     */
    public function destroy(Project $project, Card $card, Task $task)
    {
        $task->delete();
    }
}
