<?php

namespace ChatBundle\Helper\MessageHelper;

/**
 * Class Header
 * @package ChatBundle\Helper\MessageHelper
 */
class Header
{
    public $event;
    public $roomId;
    public $userId;

    /**
     * @param $event
     * @param $roomId
     * @param $userId
     */
    public function __construct($event, $roomId, $userId)
    {
        $this->event = $event;
        $this->roomId = $roomId;
        $this->userId = $userId;
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        return get_object_vars($this);
    }
}
