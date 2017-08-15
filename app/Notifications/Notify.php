<?php

namespace App\Notifications;

use App\Notification;
use Illuminate\Support\Facades\Auth;

class Notify
{
    /**
     * @var NotifiedResource
     */
    protected $notifiedResource;

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $actor;

    /**
     * @var
     */
    protected $recipients;

    /**
     * Notify constructor.
     *
     * @param NotifiedResource $notifiedResource
     */
    public function __construct(NotifiedResource $notifiedResource)
    {
        $this->notifiedResource = $notifiedResource;
        $this->actor = Auth::user();
    }

    /**
     * Sets the actor.
     *
     * @param $author
     *
     * @return $this
     */
    public function actor($author)
    {
        $this->actor = $author;

        return $this;
    }

    /**
     * Notification recipients.
     *
     * @param $users
     *
     * @return $this
     */
    public function to($users)
    {
        $this->recipients = $users;

        return $this;
    }

    /**
     * Creates a new notification.
     */
    public function create()
    {
        $notification = Notification::create(
            [
                'related_id'   => $this->notifiedResource->getId(),
                'related_type' => $this->notifiedResource->getRelatedType(),
                'actor_id'     => $this->actor->id,
                'type'         => $this->notifiedResource->getType(),
                'data'         => serialize($this->notifiedResource->getData()),
            ]
        );

        $this->assignUsers($notification);
    }

    /**
     * Assign notification to recipients.
     *
     * @param Notification $notification
     *
     * @throws \Exception
     */
    private function assignUsers(Notification $notification)
    {
        if (is_null($this->recipients)) {
            throw new \Exception('Recipient for notifications not provided');
        }

        foreach ($this->recipients as $recipient) {
            $recipient->notifications()->save($notification);
        }
    }
}
