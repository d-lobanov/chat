<?php

namespace ChatBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;

/**
 * Class StartChatCommand
 * @package ChatBundle\Command
 */
class StartChatCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('chat:start')
            ->setDescription('Greet someone');
    }

    /**
     * @param OutputInterface $output
     * @param $message
     */
    public function printPositiveMessage(OutputInterface $output, $message)
    {
        $style = new OutputFormatterStyle('white', 'green', array('blink'));
        $output->getFormatter()->setStyle('start', $style);

        $formatter = $this->getHelper('formatter');
        $formattedBlock = $formatter->formatBlock($message, 'start');
        $output->writeln($formattedBlock);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
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

        $this->printPositiveMessage($output, 'Start');

        $server->run();

    }
}
