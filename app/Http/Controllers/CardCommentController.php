<?php

namespace App\Http\Controllers;

use App\Card;
use App\Comment;
use App\Notifications\NotifiedComment;
use App\Notifications\Notify;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardCommentController extends Controller
{
    /**
     * CardCommentController constructor.
     */
    public function __construct()
    {
        $this->middleware('member:project');
    }

    /**
     * @param Project $project
     * @param Card    $card
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     * @internal param Account $account
     */
    public function store(Project $project, Card $card, Request $request)
    {
        $comment = $card->comments()->save(new Comment($request->all()));
        $comment->load('user');

        (new Notify(new NotifiedComment($comment, $card, $project)))
            ->to($project->users)
            ->create();

        return response()->make($comment);
    }

    /**
     * @param Project $project
     * @param Card    $card
     * @param Comment $comment
     *
     * @internal param Account $account
     */
    public function destroy(Project $project, Card $card, Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
    }
}