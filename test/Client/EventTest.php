<?php

namespace Test\Registry\Client;

use DateTime;
use PHPUnit_Framework_TestCase;

use Registry\Client\Store;
use Registry\Client\Model\Event;
use Registry\Client\Model\Resource;

class EventTest extends PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $store = $this
            ->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $event = new Event;
        $event
            ->setType('party')
            ->setRegisteredAt(new DateTime('2001-01-01T01:01:01+00:00'))
            ->setProperties(
                array(
                    'time.start' => '2200',
                    'time' => array(
                        'end' => '0700',
                    )
                )
            )
        ;
        $resource = new Resource;
        $event->addResource($resource, 'venue');

        $this->assertSame(
            array(
                'type' => 'party',
                'registered_at' => '2001-01-01T01:01:01+00:00',
                'properties' => array(
                    'time' => array(
                        'start' => '2200',
                        'end' => '0700',
                    ),
                ),
                'resources' => array(
                    array(
                        'role' => 'venue',
                        'resource' => $resource,
                    ),
                ),
            ),
            $event->jsonSerialize()
        );
    }

    public function testSave()
    {
        $store = $this
            ->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $event = new Event;

        $store
            ->expects($this->once())
            ->method('save')
            ->with($event)
        ;

        $event
            ->setStore($store)
            ->save()
        ;
    }
}
