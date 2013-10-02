<?php
/**
 * Created by IntelliJ IDEA.
 * User: thyde
 * Date: 9/30/13
 * Time: 6:03 PM
 * To change this template use File | Settings | File Templates.
 */

namespace CentralDesktop\JMX\Test;




use CentralDesktop\JMX\Client;
use Mockery as m;

class ClientTest extends \PHPUnit_Framework_TestCase {

    public
    function       testBeanMe() {

        $c = new Client("abcd");
        $b = $c->bean("1234");

        $this->assertInstanceOf('\CentralDesktop\JMX\Bean', $b);
        $this->assertSame($b->getName(), "1234");
    }
}