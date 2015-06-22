<?php

namespace ChatBundle\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Class ChatCommand
 * @package ChatBundle\Command
 */
abstract class ChatCommand extends ContainerAwareCommand
{
    protected $pidFileName = '/var/run/chat.pid';

    protected function getProcessId()
    {
        $file = $this->pidFileName;
        if (is_file($file)) {
            return file_get_contents($file);
        }

        return false;
    }

    protected function existProcess()
    {
        $pid = $this->getProcessId();
        if ($pid && is_numeric($pid)) {
            return posix_kill($pid, 0);
        }

        return false;
    }

    protected function checkPermission()
    {
        return is_writable($this->pidFileName) && is_readable($this->pidFileName);
    }

    /**
     * @param OutputInterface $output
     * @param string|array $event
     * @param string|array $message
     */
    protected function printMessage(OutputInterface $output, $event, $message)
    {
        $style = new OutputFormatterStyle('white', 'green');
        $output->getFormatter()->setStyle('event', $style);

        $formatter = $this->getHelper('formatter');
        $formattedBlock = $formatter->formatBlock($event, 'event');
        $output->writeln($formattedBlock);
        $output->writeln($message);
    }
}
