<?php

namespace Test\Registry\Client;

use GuzzleHttp\ClientInterface;
use PHPUnit_Framework_TestCase;

use Registry\Client\ClientBuilder;
use Registry\Client\Store;

class StoreTest extends PHPUnit_Framework_TestCase
{
    public function testSaveEvent()
    {
        $clientBuilder = $this
            ->getMockBuilder(ClientBuilder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $client = $this
            ->getMockBuilder(ClientInterface::class)
            ->setMethods(array('request'))
            ->getMockForAbstractClass()
        ;
        $clientBuilder
            ->method('build')
            ->willReturn($client)
        ;
        $clientBuilder
            ->method('setBaseUri')
            ->willReturnSelf()
        ;

        $store = new Store($clientBuilder, 'myaccount', 'mystore');
        $event = $store->createEvent('party');

        $client
            ->expects($this->once())
            ->method('request')
            ->with(
                $this->equalTo('POST'),
                $this->equalTo('events/save'),
                $this->equalTo(array('json' => $event))
            )
        ;

        $store->save($event);
    }
}
