<?php
namespace ChatBundle\Handler;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Session\Session;

class ChatHandler extends ContainerAware implements MessageComponentInterface
{

	protected $clients;

	public function __construct()
	{
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn)
	{
//        $session = $this->container->get('session.handler');
//        var_dump($session->read('9k55bui7a3lmqaofi8t13fn366'));
        //var_dump($conn->Session->all());
        //die();

		$this->clients->attach($conn);

		echo "New connection! ({$conn->resourceId})\n";
	}

	public function onMessage(ConnectionInterface $conn, $message)
    {
        $security_context = unserialize($conn->Session->get('_security_main'));
        //$username = $security_context->getUsername();
        $coockie = $conn->WebSocket;
        //var_dump($conn->WebSocket->request->getCookies());

		$numRecv = count($this->clients) - 1;
		echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
			, $conn->resourceId, $message, $numRecv, $numRecv == 1 ? '' : 's');

		$params = array(
			'author' => $conn->resourceId,
			'time' => time(),
			'text' => $message
		);
		$this->sendResponse($params);
	}

	public function sendResponse($params)
	{
		foreach ($this->clients as $client) {
			$response = $this->container->get('templating')->renderResponse(
				$view = 'ChatBundle:Default:message.html.twig',
				$params,
				$response = null
			);

			$content = $response->getContent();
			$client->send($content);
		}
	}

	public function onClose(ConnectionInterface $conn)
	{
		$this->clients->detach($conn);

		echo "Connection {$conn->resourceId} has disconnected\n";
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		echo "An error has occurred: {$e->getMessage()}\n";

		$conn->close();
	}

}