<?php

namespace ChatBundle\Session;

use Ratchet\Session\SessionProvider as RatchetProvider;
use Ratchet\ConnectionInterface;
use ChatBundle\Session\Storage\VirtualSessionStorage;

class SessionProvider extends RatchetProvider
{
    /**
     * @param ConnectionInterface $conn
     * @return mixed
     */
    public function onOpen(ConnectionInterface $conn) {
        if (!isset($conn->WebSocket) || null === ($id = $conn->WebSocket->request->getCookie(ini_get('session.name')))) {
            $saveHandler = $this->_null;
            $id = '';
        } else {
            $saveHandler = $this->_handler;
        }

        $conn->Session = new Session(new VirtualSessionStorage($saveHandler, $id, $this->_serializer));

        if (ini_get('session.auto_start')) {
            $conn->Session->start();
        }

        $result = $this->_app->onOpen($conn);
        $conn->Session->close();

        return $result;
    }
}
