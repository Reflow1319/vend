<?php

namespace App\Notifications;

interface NotifiedResource
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return array|mixed
     */
    public function getData();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return mixed
     */
    public function getRelatedType();
}
