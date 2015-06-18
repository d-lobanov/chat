<?php
namespace ChatBundle\EventListener;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Bundle\DoctrineBundle\Registry;
use ChatBundle\Helper\MessageHelper;
use ChatBundle\Service\MessageService;

/**
 * Class ChatListener
 * @package ChatBundle\EventListener
 */
class ChatListener extends ContainerAware implements MessageComponentInterface
{

    protected $rooms;
    /**
     * @var MessageService
     */
    protected $messageComponent;

    /**
     * @param MessageService $messageComponent
     */
    public function __construct(MessageService $messageComponent)
    {
        $this->messageComponent = $messageComponent;
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        if (is_null($user = $this->getUser($conn))) {
            $conn->close();
            return;
        }

        $userId = $user->getId();
        $repository = $this->getDoctrine()->getRepository('ChatBundle:User');
        $rooms = $repository->getRoomById($userId);

        foreach ($rooms as $roomId => $name) {
            $this->rooms[$roomId][$conn->resourceId] = $conn;
        }

        $this->getLogger()->addInfo("Connection: {$conn->resourceId}, username: {$user->getUsername()}");
    }

    /**
     * @param ConnectionInterface $conn
     * @param string              $json
     */
    public function onMessage(ConnectionInterface $conn, $json)
    {
        $data = json_decode($json, true);

        if (array_key_exists('event', $data) && array_key_exists('info', $data)) {
            $info = $data['info'];
            $info['userId'] = $this->getUser($conn)->getId();

            $response = $this->messageComponent->onEvent($data['event'], $info);
            $this->sendResponse($conn, $response);
        }

        return;
    }

    /**
     * @param ConnectionInterface $from
     */
    public function onClose(ConnectionInterface $from)
    {
        foreach ($this->rooms as $roomId => &$connections) {
            $key = array_search($from, $connections);
            unset($connections[$key]);

            if (empty($this->rooms[$roomId])) {
                unset($this->rooms[$roomId]);
            }
        }
        unset($connections);

        $this->getLogger()->addInfo("Connection {$from->resourceId} has disconnected");
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->getLogger()->addInfo("An error has occurred: {$e->getMessage()}");
        $conn->close();
    }

    /**
     * @param ConnectionInterface $from
     * @param MessageHelper\Response $response
     */
    protected function sendResponse(ConnectionInterface $from, MessageHelper\Response $response)
    {
        $json = json_encode($response->getJsonData());
        $event = $response->header->event;

        if ($event == 'error') {
            $from->send($json);
        } else {
            $roomId = $response->header->roomId;
            $room = $this->rooms[$roomId];

            foreach ($room as $connId => $conn) {
                $conn->send($json);
            }
        }

        $this->getLogger()->addInfo("Event: {$event}, from: {$from->resourceId}");

        return;
    }

    /**
     * @param ConnectionInterface $conn
     * @return null
     */
    protected function getUser(ConnectionInterface $conn)
    {
        $securityContext = unserialize($conn->Session->get('_security_main'));
        if (!empty($securityContext) && method_exists($securityContext, 'getUser')) {
            return $securityContext->getUser();
        }

        return null;
    }

    /**
     * @return object
     */
    protected function getLogger()
    {
        return $this->container->get('logger');
    }

    /**
     * @return object
     */
    protected function getDoctrine()
    {
        return $this->container->get('doctrine');
    }
}
