<?php

namespace ChatBundle\Service;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerAware;
use ChatBundle\Helper\MessageHelper;

class MessageService extends ContainerAware
{
    protected $roomId;
    protected $userId;

    /**
     * @param $name
     * @return mixed
     */
    protected function getRepository($name)
    {
        $doctrine = $this->container->get('doctrine');
        return $doctrine->getRepository($name);
    }

    /**
     * @param $event
     * @param $info
     * @return MessageHelper\Response
     */
    public function onEvent($event, $info)
    {
        $this->roomId = $info['roomId'];
        $this->userId = $info['userId'];

        $method = 'do'. $event;
        if($this->checkAccess($event) && method_exists($this, $method)){
            return $this->{$method}($info);
        }

        return $this->responseError('accessError');
    }

    /**
     * @param $event
     * @return bool
     */
    protected function checkAccess($event)
    {
        $repository = $this->getRepository('ChatBundle:Room');
        $access = false;

        switch($event){
            case 'delete':
                $access = $repository->isModerator($this->roomId, $this->userId);
                break;
            case 'message':
                $access = $repository->isUser($this->roomId, $this->userId);
                break;
        }

        return $access;
    }

    /**
     * @param $info
     * @return MessageHelper\Response
     */
    protected function doDelete($info)
    {
        if(array_key_exists('messId', $info) == false){
            return $this->responseError('Invalid data');
        }
        $messId = $info['messId'];

        $messageManager = $this->container->get('chat.message.manager');
        if($messageManager->delete($messId)){
            return $this->responseDelete($info);
        }

        return $this->responseError('Message not found');
    }

    /**
     * @param $info
     * @return MessageHelper\Response
     */
    protected function doMessage($info)
    {
        if(array_key_exists('text', $info) == false){
            return $this->responseError('Invalid data');
        }
        $text = $info['text'];
        $messageManager = $this->container->get('chat.message.manager');

        try{
            $message = $messageManager->save($this->userId, $this->roomId, $text);
            $info['messInfo'] = $message->getInfo();
        } catch(Exception $e){
            return $this->responseError('Message save error');
        }

        return $this->responseMessage($info);
    }

    /**
     * @param array $info
     * @return MessageHelper\Response
     */
    protected function responseError($info = array())
    {
        if(!is_array($info)){
            $info = array('message' => $info);
        }
        $header = new MessageHelper\Header('error', $this->roomId, $this->userId);
        return new MessageHelper\Response($header, $info);
    }

    /**
     * @param $info
     * @return MessageHelper\Response
     */
    protected function responseMessage($info)
    {
        $template = $this->container->get('templating')->renderResponse(
            'ChatBundle:Default:message.html.twig',
            $info['messInfo'],
            null
        );
        $info['messTemplate'] = $template->getContent();

        $header = new MessageHelper\Header('message', $this->roomId, $this->userId);
        return new MessageHelper\Response($header, $info);
    }

    /**
     * @param $info
     * @return MessageHelper\Response
     */
    protected  function responseDelete($info)
    {
        $header = new MessageHelper\Header('delete', $this->roomId, $this->userId);
        return new MessageHelper\Response($header, $info);
    }

}