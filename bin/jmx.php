#!/usr/bin/env php
<?php

require_once __DIR__.'/../vendor/autoload.php';

$application = new Symfony\Component\Console\Application();
$application->add(new CentralDesktop\JMX\Command\ListCommand());
$application->add(new CentralDesktop\JMX\Command\ReadCommand());
$application->run();
