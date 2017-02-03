<?php

namespace CentralDesktop\JMX\Command;

use CentralDesktop\JMX\Client;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReadCommand extends Command {
    protected
    function configure() {
        $this
        ->setName('read')
        ->setDescription("Displays JMX attributes")
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
        $bean   = $client->bean($input->getArgument("object"));

        $bean->read();

        $attributes = $bean->getAttributes();
        $keys       = array_keys($attributes);

        foreach ($keys as $key) {
            $value = $attributes[$key];

            if (is_array($value)) {
                $output->writeln("$key : array(" . count($value) . ")");
                foreach ($value as $el) {
                    $output->writeln("  --> $el ");
                }

            }
            else {
                $output->writeln("$key : ");
            }

        }

    }
}
