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

    public function getName(){
        return $this->_name;
    }

    /**
     * Retches remote values
     */
    public
    function read() {
        $response          = $this->_client->read($this->_name);
        $this->_attributes = $response['value'];

        $this->_logger->debug("Added attributes from read" . $this->_attributes);
    }

    public
    function exec($operation) {

    }


    /**
     * @param $attr Attribute name
     *
     * @return mixed
     * @throws JMXException
     */
    public
    function getAttribute($attr) {
        // autoload from remote if we don't have any data
        if (count($this->_attributes) == 0) {
            $this->read();
        }

        if (array_key_exists($attr, $this->_attributes)) {
            return $this->xformArray($this->_attributes[$attr]);
        }
        else {
            $this->_logger->warning("Unknown attribute",
                                    array('attribute' => $attr,
                                          'object'    => $this->_name));
            throw new JMXException("Unknown attribute $attr for {$this->_name}");
        }
    }


    /**
     *
     *Dumps all attributes
     *
     */
    public
    function getAttributes() {
        $out = array();
        foreach (array_keys($this->_attributes) as $key) {
            $out[$key] = $this->getAttribute($key);
        }

        return $out;
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

    /**
     *
     * Looks for arrays of bean references and returns Bean objects instead
     * of the raw name
     *
     * From the JSON/PHP point of view this manifests itself like
     * array (
     *        array (
     *               [objectName] => org.apache...,type=Foo
     *        )
     * )
     *
     * @param $value
     *
     * @return array
     */
    private
    function xformArray($value) {
        if (is_array($value) && count($value) > 0 &&
            array_key_exists(0, $value) && is_array($value[0]) &&
            array_key_exists('objectName', $value[0])
        ) {

            $client = $this->_client;
            return array_map(

                function ($a) use ($client) {
                    return new Bean($client, $a['objectName']);
                },
                $value);
        }
        else {
            return $value;
        }
    }


    public
    function __toString() {
        return "mbean:{$this->_name}";
    }
}