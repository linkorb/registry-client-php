<?php

namespace Registry\Client;

use DateTime;
use InvalidArgumentException;
use JsonSerializable;

use Registry\Client\Model\Event;
use Registry\Client\Model\Resource;

class Store
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * A map of model classes to their api endpoint.
     *
     * @var array
     */
    private $endpoints = array(
        Event::class => 'events/save',
    );

    /**
     * Construct the Store with an instance of ClientBuilder and the account and
     * store names.
     *
     * @param ClientBuilder $clientBuilder
     * @param string $accountName
     * @param string $storeName
     */
    public function __construct(
        ClientBuilder $clientBuilder,
        $accountName,
        $storeName
    ) {
        $base_uri = $clientBuilder->getBaseUri();
        $base_uri .= $accountName . '/' . $storeName . '/';

        $this->client = $clientBuilder
            ->setBaseUri($base_uri)
            ->build()
        ;
    }

    /**
     * Create an Event.
     *
     * @param string $type
     * @param DateTime $date
     * @param array $properties
     *
     * @return \Registry\Client\Model\Event
     */
    public function createEvent($type, DateTime $date = null, $properties = array())
    {
        $event = new Event;

        if (! $date) {
            $date = new DateTime;
        }

        return $event
            ->setStore($this)
            ->setType($type)
            ->setRegisteredAt($date)
            ->setProperties($properties)
        ;
    }

    /**
     * Create a Resource.
     *
     * @param string $name
     * @param string $type
     * @param array $properties
     *
     * @return \Registry\Client\Model\Resource
     */
    public function createResource($name, $type, $properties = array())
    {
        $resource = new Resource;

        return $resource
            ->setName($name)
            ->setType($type)
            ->setProperties($properties)
        ;
    }

    /**
     * Serialise the supplied object to json, send to the API host for saving
     * and return the reponse from the API.
     *
     * @param JsonSerializable $object
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function save(JsonSerializable $object)
    {
        $response = $this->client->request(
            'POST',
            $this->getEndpoint($object),
            array(
                'json' => $object,
            )
        );
        return $response;
    }

    private function getEndpoint($object)
    {
        $class = get_class($object);

        if (! array_key_exists($class, $this->endpoints)) {
            throw new InvalidArgumentException(
                sprintf(
                    'No endpoint exists for objects of class "%s".',
                    $class
                )
            );
        }

        return $this->endpoints[$class];
    }
}