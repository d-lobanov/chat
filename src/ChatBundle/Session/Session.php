<?php

namespace ChatBundle\Session;

use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

/**
 * Class Session, add to symfony session method close for PDO session
 * @package ChatBundle\Session
 */
class Session extends SymfonySession
{
    /**
     * @return mixed
     */
    public function close()
    {
        if (method_exists($this->storage, 'close')) {
            return $this->storage->close();
        }
        return false;
    }
}
