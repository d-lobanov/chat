<?php

namespace ChatBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class RoomController
 * @package ChatBundle\Controller
 */
class RoomController extends Controller
{
    /**
     * @return Response
     */
    public function exampleAction()
    {
        $response = new Response('Test');
        $response->headers->setCookie(new Cookie('test', '123'));

        return $response;
    }

    /**
     * @param string $name
     * @return Response
     */
    public function showAction($name)
    {
        $roomRepository = $this->getDoctrine()->getRepository('ChatBundle:Room');
        $room = $roomRepository->existsRoomByName($name);
        if ($room == false) {
            return new Response('Room not found', 404);
        }

        $user = $this->getUser();
        $roomId = $room->getId();
        $isUser = $roomRepository->isUser($roomId, $user->getId());
        if ($isUser == false) {
            return new Response('Access denied', 403);
        }

        $isModerator = $roomRepository->isModerator($roomId, $user->getId());
        $messages = $roomRepository->getMessagesGroupByDate($room->getId());
        $rooms = $user->getAllRoomsAsArray();

        return $this->render('ChatBundle:Default:index.html.twig', array(
            'messages' => $messages,
            'rooms' => $rooms,
            'currRoom' => $roomId,
            'isModerator' => $isModerator
        ));
    }

    /**
     * Homepage, redirect to the first room
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction()
    {
        $rooms = $this->getUser()->getRooms();
        if ($rooms->isEmpty()) {
            return new Response('Room not found', 404);
        }

        $name = $rooms->first()->getName();
        return $this->redirectToRoute('room_show', array('name' => $name));
    }
}
