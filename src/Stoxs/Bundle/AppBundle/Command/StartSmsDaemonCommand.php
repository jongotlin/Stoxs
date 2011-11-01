<?php

namespace Stoxs\Bundle\AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use CodeMeme\Bundle\CodeMemeDaemonBundle\Daemon;

class StartSmsDaemonCommand extends ContainerAwareCommand
{
    protected function configure()
    {   
        $this->setName('sms-daemon:start')
             ->setDescription('Starts the SMS daemon')
             ->setHelp(<<<EOT
<info>{$this->getName()}</info> Run the SMS daemon in the background.
EOT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $daemon = new Daemon($this->getContainer()->getParameter('sms_sender.daemon.options'));
        $daemon->start();
        
        while ($daemon->isRunning()) {
            $this->getContainer()->get('stoxs.sms_sender')->run();
        }
        
        $daemon->stop();
    }

}