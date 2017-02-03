<?php

namespace CentralDesktop\JMX\Command;

use CentralDesktop\JMX\Client;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends BaseCommand
{
    protected function configure()
    {
        $this
            ->addArgument('uri', InputArgument::REQUIRED, 'URL for the JMX Jolokia endpoint')
            ->addOption('user', '', InputOption::VALUE_REQUIRED, 'Username')
            ->addOption('password', '', InputOption::VALUE_REQUIRED, 'Password')
        ;
    }

    protected function buildClient(InputInterface $input)
    {
        return new Client(
            $input->getArgument('uri'),
            $input->getOption('user'),
            $input->getOption('password')
        );
    }
}
