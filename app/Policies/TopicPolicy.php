<?php

namespace App\Policies;

use App\Topic;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the topic.
     *
     * @param \App\User  $user
     * @param \App\Topic $topic
     *
     * @return mixed
     */
    public function update(User $user, Topic $topic)
    {
        return $user->role === 'admin' || $topic->user_id === $user->id;
    }
}
