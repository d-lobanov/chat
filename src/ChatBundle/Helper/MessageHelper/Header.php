<?php

namespace ChatBundle\Helper\MessageHelper;

class Header
{
    public $event;
    public $roomId;
    public $userId;

    public function __construct($event, $roomId, $userId)
    {
        $this->event = $event;
        $this->roomId = $roomId;
        $this->userId = $userId;
    }

    public function getJsonData()
    {
        return get_object_vars($this);
    }

}