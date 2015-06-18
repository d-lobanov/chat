<?php

namespace ChatBundle\Session\Storage;

use Ratchet\Session\Storage\VirtualSessionStorage as RatchetSessionStorage;

/**
 * Class VirtualSessionStorage
 * @package ChatBundle\Session\Storage
 */
class VirtualSessionStorage extends RatchetSessionStorage
{
    /**
     * Calls handler close
     */
    public function close()
    {
        $this->getSaveHandler()->close();
    }
}
