<?php

namespace Test\Registry\Client;

use PHPUnit_Framework_TestCase;

use Registry\Client\Model\Resource;

class ResourceTest extends PHPUnit_Framework_TestCase
{
    public function testJsonSerialize()
    {
        $resource = new Resource;
        $resource
            ->setName('water')
            ->setType('element')
            ->setProperties(
                array(
                    'triple-point' => array(
                        'temp' => '273.16 K',
                    ),
                    'triple-point.pressure' => '611.657 Pa'
                )
            )
        ;

        $this->assertSame(
            array(
                'name' => 'water',
                'type' => 'element',
                'properties' => array(
                    'triple-point' => array(
                        'temp' => '273.16 K',
                        'pressure' => '611.657 Pa'
                    ),
                ),
            ),
            $resource->jsonSerialize()
        );
    }
}
