<?php

namespace Registry\Client\Model;

use DateTime;
use JsonSerializable;

use Registry\Common\DottedPropertyTrait;

use Registry\Client\Store;

/**
 * A representation of an Event.
 */
class Event implements JsonSerializable
{
    use DottedPropertyTrait;

    /**
     * The properties of the Event.
     *
     * @var array
     */
    protected $properties = array();
    /**
     * The date and time of the Event.
     *
     * @var \DateTime
     */
    protected $registered_at;
    /**
     * The Resources associated with the Event.
     *
     * @var array
     */
    protected $resources = array();
    /**
     * The type of the Event.
     *
     * @var string
     */
    protected $type;

    /**
     * @var \Registry\Client\Store
     */
    private $store;

    /**
     * Set the type of Event.
     *
     * @param string $type
     *
     * @return \Registry\Client\Model\Event
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the date and time of the Event.
     *
     * @param DateTime $registered_at
     *
     * @return \Registry\Client\Model\Event
     */
    public function setRegisteredAt(DateTime $registered_at)
    {
        $this->registered_at = $registered_at;

        return $this;
    }

    /**
     * Set the properties of the Event.
     *
     * @param array $properties
     *
     * @return \Registry\Client\Model\Event
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * Add a property of the Event.
     *
     * @param string $name
     * @param number|string|array $value
     *
     * @return \Registry\Client\Model\Event
     */
    public function addProperty($name, $value)
    {
        $this->properties[$name] = $value;

        return $this;
    }

    /**
     * Add a Resource associated with the Event and its role.
     *
     * @param Resource $resource
     * @param string $role
     *
     * @return \Registry\Client\Model\Event
     */
    public function addResource(Resource $resource, $role)
    {
        $this->resources[] = array(
            'role' => $role,
            'resource' => $resource,
        );

        return $this;
    }

    /**
     * Set the Registry Store.
     *
     * @param Store $store
     *
     * @return \Registry\Client\Model\Event
     */
    public function setStore(Store $store)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Save the Event.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function save()
    {
        return $this->store->save($this);
    }

    public function jsonSerialize()
    {
        $properties = array();
        foreach ($this->flatten($this->properties) as $k => $v) {
            $properties[utf8_encode($k)] = utf8_encode($v);
        }
        return array(
            'type' => utf8_encode($this->type),
            'registered_at' => utf8_encode($this->registered_at->format('c')),
            'properties' => $this->unflatten($properties),
            'resources' => $this->resources,
        );
    }
}
