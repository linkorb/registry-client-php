<?php

namespace Test\Registry\Client;

use PHPUnit_Framework_TestCase;
use ReflectionClass;
use Registry\Client\ClientBuilder;
use Registry\Client\ClientOptions;

class ClientBuilderProcessBaseUriTest extends PHPUnit_Framework_TestCase
{
    public function testReturnsWithoutChangingAnExistingBaseUri()
    {
        $baseUri = 'https://preset.example/custom';

        $this->assertSame(
            $baseUri,
            $this->processBaseUri(array(
                ClientOptions::BASE_URI => $baseUri,
                ClientOptions::API_HOST => 'ignored.example',
                ClientOptions::API_VERSION => 'v9',
                ClientOptions::SECURE_HTTP => true,
            ))
        );
    }

    public function testBuildsHttpLocalhostUriWhenOptionsAreAbsent()
    {
        $this->assertSame(
            'http://localhost/api/v1/',
            $this->processBaseUri(array())
        );
    }

    public function testBuildsHttpsUriFromHostVersionAndSecureFlag()
    {
        $this->assertSame(
            'https://registry.example/api/v2/',
            $this->processBaseUri(array(
                ClientOptions::SECURE_HTTP => true,
                ClientOptions::API_HOST => 'registry.example',
                ClientOptions::API_VERSION => 'v2',
            ))
        );
    }

    public function testKeepsHttpWhenSecureFlagIsFalse()
    {
        $this->assertSame(
            'http://localhost/api/v1/',
            $this->processBaseUri(array(
                ClientOptions::SECURE_HTTP => false,
            ))
        );
    }

    private function processBaseUri(array $config)
    {
        $reflection = new ReflectionClass('Registry\Client\ClientBuilder');
        $builder = $reflection->newInstanceWithoutConstructor();

        $configProperty = $reflection->getProperty('config');
        $configProperty->setAccessible(true);
        $configProperty->setValue($builder, $config);

        $method = $reflection->getMethod('processBaseUri');
        $method->setAccessible(true);
        $method->invoke($builder);

        return $builder->getBaseUri();
    }
}
