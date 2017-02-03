<?php

namespace CentralDesktop\JMX;

use Guzzle\Http;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\NullLogger;

class Client implements LoggerAwareInterface
{
    private $logger;

    private $uri;
    private $username;
    private $password;

    /** @var \Guzzle\Http\Client */
    private $g;

    /**
     * @param $uri
     * @param $username
     * @param $password
     */
    public function __construct($uri, $username = '', $password = '') {
        $this->uri      = $uri;
        $this->username = $username;
        $this->password = $password;

        $this->logger = new NullLogger();

        $client = new Http\Client($uri);
        $client->setDefaultOption('auth', array($username, $password, 'Basic'));
        $client->setDefaultOption('timeout', 5);
        $client->setDefaultOption('connect_timeout', 1);

        $this->setClient($client);
    }

    public function setClient(\Guzzle\Http\Client $client){
        $this->g = $client;
    }

    public function bean($name) {
        $bean = new Bean($this, $name);
        $bean->setLogger($this->logger);

        return $bean;
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     *
     * @return null
     */
    public function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function read($name)
    {
        return $this->sendRequest("read/{$name}");
    }

    public function list()
    {
        $response = $this->sendRequest('list');

        $beans = [];
        foreach($response['value'] as $domain => $list) {
            foreach($list as $name => $metadata) {
                $beans[] = $this->bean($domain.':'.$name);
            }
        }

        return $beans;
    }

    protected function sendRequest($path)
    {
        $request = $this->g->get($path);

        return $request->send()->json();
    }
}
