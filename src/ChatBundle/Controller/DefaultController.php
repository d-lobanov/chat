<?php

namespace ChatBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function indexAction()
    {
        $response = new Response('Test');
        $response->headers->setCookie(new Cookie('test', '123'));
        return $response;

        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/room", name="room")
     */
    public function roomAction()
    {
        $messages = [];
        $rooms = [];

        return $this->render('ChatBundle:Default:index.html.twig', array(
            'messages' => $messages,
            'rooms' => $rooms
        ));
    }
}
