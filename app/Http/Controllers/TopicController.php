<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    /**
     * TopicController constructor.
     */
    public function __construct()
    {
        $this->middleware('role:editor', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('member:topic', ['except' => ['store', 'index']]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topics = Topic::whereHas('users', function ($query) {
            $query->where('topic_user.user_id', Auth::user()->id);
        })
            ->with('latestMessage', 'latestMessage.user', 'users')
            ->get();

        return response()->make($topics);
    }

    /**
     * @param \App\Topic $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        $topic->load('latestMessage', 'users');

        return response()->make($topic);
    }

    /**
     * @param \App\Http\Requests\TopicRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(TopicRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $topic = Topic::create($data);
        $this->attachUsers($topic, $request);

        return $this->show($topic);
    }

    /**
     * @param \App\Http\Requests\TopicRequest $request
     * @param \App\Topic                      $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());
        $this->attachUsers($topic, $request);

        return $this->show($topic);
    }

    /**
     * @param \App\Topic $topic
     *
     * @throws \Exception
     *
     * @return void
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('update', $topic);

        $topic->delete();
    }

    /**
     * @param \App\Topic               $topic
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    private function attachUsers(Topic $topic, Request $request)
    {
        $users = array_pluck($request->input('users'), 'id');

        if (!in_array(Auth::user()->id, $users, true)) {
            $users[] = Auth::user()->id;
        }

        $topic->users()->sync($users);
    }
}
