<?php
/**
 * Created by IntelliJ IDEA.
 * User: thyde
 * Date: 9/30/13
 * Time: 6:05 PM
 * To change this template use File | Settings | File Templates.
 */

namespace CentralDesktop\JMX\Tests;


use CentralDesktop\JMX\Bean;
use Mockery as m;

class BeanTest extends \PHPUnit_Framework_TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

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

        $this->assertSame($bean->getAttribute('foo'), $r["value"]['foo']);
        $this->assertSame($bean->getAttribute('baz'), $r["value"]['baz']);
    }

    public
    function testReadObject() {
        $client = m::mock('\CentralDesktop\JMX\Client');
        $obj    = 'org.apache.activemq:brokerName="activemq.test.com",type=Broker';
        $bean   = new Bean($client, $obj);

        $r = array(
            "value" => array(
                "foo"   => "bar",
                "baz"   => "hello world",
                "super" => array(
                    array("objectName" => "abcdefg"),
                    array("objectName" => "123456")
                )
            )
        );

        $client->shouldReceive('read')->once()->andReturn($r);


        $super = $bean->getAttribute('super');

        $this->assertArrayHasKey(0, $super);

        $abc = $super[0];
        $this->assertInstanceOf('\CentralDesktop\JMX\Bean', $abc);
        $this->assertSame($abc->getName(), "abcdefg");


        $_123 = $super[1];
        $this->assertInstanceOf('\CentralDesktop\JMX\Bean', $_123);
        $this->assertSame($_123->getName(), "123456");
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
            "value" => array(
                "foo" => "bar"
            )
        );

        $client->shouldReceive('read')->once()->andReturn($r);

        $this->assertSame($bean->getAttribute('baz'), $r["value"]['baz']);
    }


    public
    function testToString() {
        $client = m::mock('\CentralDesktop\JMX\Client');
        $obj    = '1234';
        $bean   = new Bean($client, $obj);

        $this->assertSame($bean->__toString(), "mbean:{$obj}");
        $this->assertSame("$bean", "mbean:{$obj}");
        $this->assertSame("{$bean}", "mbean:{$obj}");
    }
}
