<?php

namespace ChatBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class DefaultController
 * @package ChatBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     * @return Response
     */
    public function indexAction()
    {
        $response = new Response('Test');
        $response->headers->setCookie(new Cookie('test', '123'));

        return $response;
    }

    /**
     * @Route("/room/{name}", name="room")
     * @param string $name
     * @return Response
     */
    public function roomAction($name)
    {
        $roomRepository = $this->getDoctrine()->getRepository('ChatBundle:Room');
        $room = $roomRepository->existsRoomByName($name);
        if ($room == false) {
            return new Response('Room not found', 404);
        }

        $user = $this->getUser();
        $isUser = $roomRepository->isUser($room->getId(), $user->getId());
        if ($isUser == false) {
            return new Response('Access denied', 403);
        }

        $isModerator = $roomRepository->isModerator($room->getId(), $user->getId());
        $messages = $roomRepository->getMessagesGroupByDate($room->getId());
        $rooms = $user->getAllRoomsAsArray();

        return $this->render('ChatBundle:Default:index.html.twig', array(
            'messages' => $messages,
            'rooms' => $rooms,
        ));
    }
}
