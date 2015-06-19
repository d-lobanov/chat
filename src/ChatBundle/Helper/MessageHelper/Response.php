<?php

namespace ChatBundle\Helper\MessageHelper;

use ChatBundle\Helper\MessageHelper\Header;

class Response
{
    /**
     * @var Header
     */
    public $head;

    public $body;

    /**
     * @param Header $head
     * @param array $body
     */
    public function __construct(Header $head, $body = array())
    {
        $this->head = $head;
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        $var = get_object_vars($this);
        foreach ($var as &$value) {
            if (is_object($value) && method_exists($value, 'getJsonData')) {
                $value = $value->getJsonData();
            }
        }
        return $var;
    }
}
