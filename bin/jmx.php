#!/usr/bin/env php
<?php


use CentralDesktop\JMX\Client;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

require_once("vendor/autoload.php");


class ReadCommand extends Command {
    protected
    function configure() {
        $this
        ->setName('read')
        ->setDescription("Get's JMX attributes")
        ->addArgument(
                'uri',
                InputArgument::REQUIRED,
                'URL for the JMX Jolokia endpoint'
            )
        ->addArgument(
                'object',
                InputArgument::REQUIRED,
                'Name of the object you are fetching'
            )
        ->addOption('user', '', InputOption::VALUE_REQUIRED, "Username")
        ->addOption('password', '', InputOption::VALUE_REQUIRED, "Password");
    }

    protected
    function execute(InputInterface $input, OutputInterface $output) {
        $client = new Client($input->getArgument("uri"),
                             $input->getOption("user"),
                             $input->getOption("password"));
        $bean = $client->bean($input->getArgument("object"));

        $bean->read();

        $output->write(print_r($bean->getAttributes(), true));
    }
}



$application = new Application();
$application->add(new ReadCommand());
$application->run();
