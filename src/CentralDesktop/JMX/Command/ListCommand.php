<?php

namespace CentralDesktop\JMX\Command;

use CentralDesktop\JMX\Client;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('beans')
            ->setDescription('Lists MBeans')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->buildClient($input);

        $beans = $client->list();

        foreach ($beans as $bean) {
            $output->writeln($bean->getName());
        }
    }
}
