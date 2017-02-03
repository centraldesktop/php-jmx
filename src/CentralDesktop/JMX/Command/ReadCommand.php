<?php

namespace CentralDesktop\JMX\Command;

use CentralDesktop\JMX\Client;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReadCommand extends Command
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('read')
            ->setDescription('Displays MBean attributes')
            ->addArgument(
                'object',
                InputArgument::REQUIRED,
                'Name of the object you are fetching'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = $this->buildClient($input);

        $bean = $client->bean($input->getArgument('object'));

        $attributes = $bean->getAttributes();
        $keys = array_keys($attributes);

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
