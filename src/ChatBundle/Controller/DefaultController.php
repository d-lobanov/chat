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
     * @Route("/room", name="room")
     * @return Response
     */
    public function roomAction()
    {
        $messages = [];
        $rooms = $this->getUser()->getRoomsAsArray();

        return $this->render('ChatBundle:Default:index.html.twig', array(
            'messages' => $messages,
            'rooms' => $rooms,
        ));
    }
}
