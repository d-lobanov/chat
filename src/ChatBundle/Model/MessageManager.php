<?php

namespace ChatBundle\Model;

use Doctrine\Common\Persistence\ObjectManager;
use ChatBundle\Entity\Message;

/**
 * Class MessageManager
 * @package ChatBundle\Model
 */
class MessageManager
{
    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $authorId
     * @param $roomId
     * @param $text
     * @return Message
     */
    public function save($authorId, $roomId, $text)
    {
        $author = $this->em->find('ChatBundle:User', $authorId);
        $room = $this->em->find('ChatBundle:Room', $roomId);

        $message = new Message();
        $message->setText($text);
        $message->setUser($author);
        $message->setRoom($room);
        $message->setCreated(new \DateTime());

        $this->em->persist($message);
        $this->em->flush();

        return $message;
    }

    /**
     * @param int $messageId
     * @return bool
     */
    public function delete($messageId)
    {
        $message = $this->em->find('ChatBundle:Message', $messageId);
        if (!is_null($message)) {
            $this->em->remove($message);
            $this->em->flush();
            return true;
        }
        return false;
    }
}
