<?php


namespace CentralDesktop\JMX;


use Guzzle\Http;
use Psr\Log\LoggerInterface;

class Client implements \Psr\Log\LoggerAwareInterface {

    private $logger;

    private $uri;
    private $username;
    private $password;

    private $g;

    /**
     * @param $uri
     * @param $username
     * @param $password
     */
    public
    function __construct($uri, $username, $password) {
        error_log("$username $password");
        $this->uri      = $uri;
        $this->username = $username;
        $this->password = $password;

        $this->logger = new \Psr\Log\NullLogger();

        $this->g = new Http\Client($uri);

        $this->g->setDefaultOption('auth',
                                   array($username, $password, 'Basic'));

        $this->g->setDefaultOption('timeout', 5);
        $this->g->setDefaultOption('connect_timeout', 1);
    }

    /**
     */
    public
    function connect($uri, $username, $password) {
        $this->logger->debug("Connecting to $uri");
    }


    public
    function bean($name) {
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
    public
    function setLogger(LoggerInterface $logger) {
        $this->logger = $logger;
    }


    public
    function read($name) {
        $request  = $this->g->get("read/{$name}");
        $response = $request->send();

        return $response->json();
    }
}