<?php
/**
 * Created by IntelliJ IDEA.
 * User: thyde
 * Date: 9/30/13
 * Time: 5:48 PM
 * To change this template use File | Settings | File Templates.
 */

namespace CentralDesktop\JMX;


use Psr\Log\LoggerInterface;

class Bean implements \Psr\Log\LoggerAwareInterface {
    /** @var \Psr\Log\LoggerInterface */
    private $_logger = null;
    private $_client = null;
    private $_name = null;
    private $_attributes = array();

    public
    function __construct(Client $client, $name) {
        $this->_client = $client;
        $this->_name   = $name;

        $this->_logger = new \Psr\Log\NullLogger();
    }

    public
    function read() {
        $response =  $this->_client->read($this->_name);
        $this->_attributes = $response['value'];

        $this->_logger->debug("Added attributes from read". $this->_attributes);
    }

    public
    function exec($operation) {

    }


    public
    function __get($attr) {
        // autoload from remote if we don't have any data
        if (count($this->_attributes) == 0) {
            $this->read();
        }

        if (array_key_exists($attr, $this->_attributes)) {
            return $this->_attributes[$attr];
        }
        else {
            $this->_logger->warning("Unknown attribute",
                                    array('attribute' => $attr,
                                          'object'    => $this->_name));
            throw new JMXException("Unknown attribute $attr for {$this->_name}");
        }
    }

    public function getAttributes(){
        return $this->_attributes;
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
        $this->_logger = $logger;
    }
}