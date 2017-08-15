<?php

namespace App\Http\Controllers;

use App\Column;
use App\Favorite;
use App\Http\Requests\ProjectRequest;
use App\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * ProjectController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:admin', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('member:project', ['only' => ['show', 'favorite', 'update']]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with('users', 'columns')
            ->withCount('completedCards', 'cards')
            ->where(function ($query) {
                $query->where('is_archive', request('archive') === '1');
            })->whereHas('users', function ($query) {
                $query->where('id', Auth::user()->id);
            })->get();

        return response()->make($projects);
    }

    /**
     * @param Project $project
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $project->load('users', 'columns');

        return response()->make($project);
    }

    /**
     * @param ProjectRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create($request->all());
        $this->save($project, $request);

        return $this->show($project);
    }

    /**
     * @param ProjectRequest $request
     * @param Project        $project
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->all());
        $this->save($project, $request);

        return $this->show($project);
    }

    /**
     * @param Project        $project
     * @param ProjectRequest $request
     */
    private function save(Project $project, $request)
    {
        // Sync users
        $userIds = array_pluck($request->input('users'), 'id');
        $project->users()->sync($userIds);

        // Delete columns which not needed
        $toDelete = array_diff(
            array_pluck($project->columns, 'id'),
            array_pluck($request->input('columns'), 'id')
        );

        Column::destroy($toDelete);

        $columns = [];
        foreach ($request->input('columns') as $column) {
            if (isset($column['id'])) {
                $c = Column::find($column['id']);
                $c->update($column);
                $columns[] = $c;
            } else {
                $columns[] = new Column($column);
            }
        }

        $project->columns()->saveMany($columns);
    }

    /**
     * @param Project $project
     */
    public function destroy(Project $project)
    {
        $project->delete();
    }
}
