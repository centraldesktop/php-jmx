<?php
/**
 * Created by IntelliJ IDEA.
 * User: thyde
 * Date: 9/30/13
 * Time: 6:05 PM
 * To change this template use File | Settings | File Templates.
 */

namespace CentralDesktop\JMX\Test;


use CentralDesktop\JMX\Bean;
use Mockery as m;

class BeanTest extends \PHPUnit_Framework_TestCase {


    public
    function testRead() {
        $client = m::mock('\CentralDesktop\JMX\Client');
        $obj    = 'org.apache.activemq:brokerName="activemq.test.com",type=Broker';
        $bean   = new Bean($client, $obj);

        $r = array(
            "value" => array(
                "foo" => "bar",
                "baz" => "hello world"
            )
        );

        $client->shouldReceive('read')->once()->andReturn($r);

        $this->assertSame($bean->foo, $r['foo']);
        $this->assertSame($bean->baz, $r['baz']);

    }

    /**
     *
     * @expectedException \CentralDesktop\JMX\JMXException
     * */
    public
    function testReadBadProperty() {
        $client = m::mock('\CentralDesktop\JMX\Client');
        $obj    = 'org.apache.activemq:brokerName="activemq.test.com",type=Broker';
        $bean   = new Bean($client, $obj);

        $r = array(
            "foo" => "bar",
        );

        $client->shouldReceive('read')->once()->andReturn($r);

        $this->assertSame($bean->baz, $r['baz']);
    }
}