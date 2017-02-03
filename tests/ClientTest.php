<?php

namespace CentralDesktop\JMX\Tests;

use CentralDesktop\JMX\Client;
use CentralDesktop\JMX\Bean;
use Mockery as m;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    use MockeryPHPUnitIntegration;

    public function testBeanMe()
    {
        $c = new Client("abcd");
        $b = $c->bean("1234");

        $this->assertInstanceOf('\CentralDesktop\JMX\Bean', $b);
        $this->assertSame($b->getName(), "1234");
    }

    public function testList()
    {
        $client = m::mock('\CentralDesktop\JMX\Client[sendRequest]', [''])->shouldAllowMockingProtectedMethods();

        $client->shouldReceive('sendRequest')->once()->andReturn([
            'value' => [
                'java.lang' => [
                    'name=Foo' => [],
                    'name=Bar' => [],
                ],
                'java.nio' => [
                    'type=BufferPool' => [],
                ],
            ],
        ]);

        $beans = $client->list();

        $this->assertCount(3, $beans);
        $this->assertInstanceOf('\CentralDesktop\JMX\Bean', $beans[0]);
        $this->assertSame('java.lang:name=Foo', $beans[0]->getName());
    }
}
