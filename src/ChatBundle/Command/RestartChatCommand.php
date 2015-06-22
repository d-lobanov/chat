<?php

namespace ChatBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class StartChatCommand
 * @package ChatBundle\Command
 */
class RestartChatCommand extends ChatCommand
{
    protected function configure()
    {
        $this
            ->setName('chat:restart')
            ->setDescription('Restart chat');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopCommand = $this->getApplication()->find('chat:stop');
        $arguments = array(
            'command' => 'chat:stop',
        );

        $input = new ArrayInput($arguments);
        $stopCommand->run($input, $output);

        $startCommand = $this->getApplication()->find('chat:start');
        $arguments = array(
            'command' => 'chat:start',
        );
        $input = new ArrayInput($arguments);
        $statusCode = $startCommand->run($input, $output);
        if ($statusCode != 0) {
            return E_ERROR;
        }

        return 0;
    }
}
