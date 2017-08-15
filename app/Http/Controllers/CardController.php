<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Requests\CardRequest;
use App\Notifications\NotifiedCard;
use App\Notifications\Notify;
use App\Project;
use App\Services\UploadService;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * CardController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:editor', [
            'except' => ['index', 'upload'],
        ]);

        $this->middleware('member:project');
    }

    /**
     * @param \App\Project $project
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        $cards = Card::whereProjectId($project->id)
            ->with(
                'tasks',
                'comments',
                'logs',
                'logs.user',
                'assigned',
                'files'
            )->get();

        return response()->make($cards);
    }

    /**
     * @param \App\Project $project
     * @param \App\Card    $card
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, Card $card)
    {
        $card->load(
            'tasks',
            'comments',
            'logs',
            'logs.user',
            'assigned',
            'files'
        );

        return response()->make($card);
    }

    /**
     * @param \App\Project                   $project
     * @param \App\Http\Requests\CardRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project, CardRequest $request)
    {
        return $this->save($project, $request);
    }

    /**
     * @param \App\Project                   $project
     * @param \App\Http\Requests\CardRequest $request
     * @param \App\Card                      $card
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Project $project, CardRequest $request, Card $card)
    {
        return $this->save($project, $request, $card);
    }

    /**
     * @param \App\Project             $project
     * @param \Illuminate\Http\Request $request
     * @param \App\Card                $card
     *
     * @return \Illuminate\Http\Response
     */
    public function column(Project $project, Request $request, Card $card)
    {
        $card->update($request->all());

        return response()->make($card);
    }

    /**
     * @param \App\Project             $project
     * @param \Illuminate\Http\Request $request
     * @param \App\Card|null           $card
     *
     * @return \Illuminate\Http\Response
     */
    private function save(Project $project, Request $request, Card $card = null)
    {
        $previous = $card ? $card->replicate() : new Card();
        $isNew = is_null($card);

        if (!$card) {
            $data = $request->all();
            $card = Card::create($data);

            // Since it's a new card, we don't need to fetch
            // relations from the db, simply we create empty arrays
            $card->tasks = [];
            $card->files = [];
            $card->logs = [];
            $card->comments = [];
        } else {
            $card->update($request->all());
        }

        $card->load('assigned');

        notify(new NotifiedCard($previous, $card, $project, $isNew))
            ->to($project->users)
            ->create();

        return response()->make($card);
    }

    /**
     * @param Project $project
     * @param Card    $card
     */
    public function destroy(Project $project, Card $card)
    {
        $card->delete();
    }

    /**
     * @param Project                  $project
     * @param \Illuminate\Http\Request $request
     * @param Card                     $card
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Project $project, Request $request, Card $card)
    {
        $uploadService = new UploadService($card);
        $file = $uploadService->upload($request);

        return response()->make($file);
    }
}
