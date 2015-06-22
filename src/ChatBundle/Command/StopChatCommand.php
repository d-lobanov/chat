<?php

namespace ChatBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;

/**
 * Class StartChatCommand
 * @package ChatBundle\Command
 */
class StopChatCommand extends ChatCommand
{
    protected function configure()
    {
        $this
            ->setName('chat:stop')
            ->setDescription('Stop chat');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->checkPermission() == false) {
            $this->printMessage($output, 'Error', "Current user has no permission");
            return E_ERROR;
        }

        if ($this->existProcess() == false) {
            $this->printMessage($output, 'Error', "Chat already stopped");
            return E_ERROR;
        }

        if (posix_kill($this->getProcessId(), SIGTERM) == false) {
            $this->printMessage($output, 'Error', posix_get_last_error());
            return E_ERROR;
        }

        $this->printMessage($output, 'Stop', 'Chat stopped');
        return 0;
    }
}
