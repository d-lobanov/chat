<?php

namespace ChatBundle\Helper\MessageHelper;

use ChatBundle\Helper\MessageHelper\Header;

class Response
{
    /**
     * @var Header
     */
    public $header;

    public $body;

    public function __construct(Header $header, $body = array())
    {
        $this->header = $header;
        $this->body = $body;
    }

    public function getJsonData(){
        $var = get_object_vars($this);
        foreach($var as &$value){
            if(is_object($value) && method_exists($value,'getJsonData')){
                $value = $value->getJsonData();
            }
        }
        return $var;
    }
}