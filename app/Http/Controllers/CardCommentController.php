<?php

namespace App\Http\Controllers;

use App\Card;
use App\Comment;
use App\Http\Requests\CommentRequest;
use App\Notifications\NotifiedComment;
use App\Notifications\Notify;
use App\Project;

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
     * @param Project        $project
     * @param Card           $card
     * @param CommentRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Project $project, Card $card, CommentRequest $request)
    {
        $comment = $card->comments()->save(new Comment($request->all()));
        $comment->load('user');

        notify(new NotifiedComment($comment, $card, $project))
            ->to($project->users)
            ->create();

        return response()->make($comment);
    }

    /**
     * @param Project $project
     * @param Card    $card
     * @param Comment $comment
     */
    public function destroy(Project $project, Card $card, Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
    }
}
