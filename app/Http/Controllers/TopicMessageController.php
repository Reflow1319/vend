<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\MessageRequest;
use App\Message;
use App\Notifications\NotifiedMessage;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicMessageController extends Controller
{
    /**
     * TopicMessageController constructor.
     */
    public function __construct()
    {
        $this->middleware('member:topic');
    }

    /**
     * @param \App\Topic $topic
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Topic $topic)
    {
        $messages = Message::with('user', 'files')
            ->whereTopicId($topic->id)
            ->latest()
            ->paginate(50);

        notifyRead('message', $messages->pluck('id')->toArray())
            ->read();

        return response()->make($messages);
    }

    /**
     * @param \App\Topic             $topic
     * @param MessageRequest|Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Topic $topic, MessageRequest $request)
    {
        $message = $this->save($topic, $request);
        $message->load('user', 'files');

        return response()->make($message);
    }

    /**
     * @param Topic   $topic
     * @param Message $message
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic, Message $message)
    {
        $message->load('user');

        return response()->make($message);
    }

    /**
     * @param \App\Topic               $topic
     * @param \App\Message             $message
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Topic $topic, Message $message, Request $request)
    {
        $message = $this->save($topic, $request, $message);
        $message->load('user', 'files');

        return response()->make($message);
    }

    /**
     * @param \App\Topic   $topic
     * @param \App\Message $message
     */
    public function destroy(Topic $topic, Message $message)
    {
        DB::table('files')->where('type', 'message')
            ->where('parent', $message->id)->delete();

        $message->delete();
    }

    /**
     * @param \App\Topic               $topic
     * @param \Illuminate\Http\Request $request
     * @param \App\Message|null        $message
     *
     * @return \App\Message
     */
    private function save(
        Topic $topic,
        Request $request,
        Message $message = null
    ) {
        if ($message) {
            $message->update($request->only('message'));
        } else {
            $message = new Message($request->only('message'));
        }

        $topic->messages()->save($message);
        $topic->latestMessage()->save($message);

        notify(new NotifiedMessage($message, $topic))
            ->to($topic->users)
            ->create();

        // Attach files if we have any

        if ($request->input('files')) {
            $fileIds = array_pluck($request->input('files'), 'id');
            $files = File::whereIn('id', $fileIds)->get();
            $message->files()->saveMany($files);
        }

        return $message;
    }
}
