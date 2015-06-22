<?php

namespace ChatBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;

/**
 * Class StartChatCommand
 * @package ChatBundle\Command
 */
class StartChatCommand extends ChatCommand
{
    protected function configure()
    {
        $this
            ->setName('chat:start')
            ->setDescription('Start chat');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->existProcess()) {
                $this->printMessage($output, 'Error', "Chat already running");
            return E_ERROR;
        }

        $pid = pcntl_fork();
        if ($pid) {
            file_put_contents($this->pidFileName, $pid);
            $this->printMessage($output, 'Start', "Process id: {$pid}");
            return 0;
        }
        posix_setsid();

        $this->startChat();

    }

    protected function startChat()
    {
        $sessionProvider = $this->getContainer()->get('chat.session.provider');

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    $sessionProvider
                )
            ),
            8080
        );

        $server->run();
    }
}
