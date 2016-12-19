<?php

namespace Registry\Client\Model;

use JsonSerializable;

use Registry\Common\DottedPropertyTrait;

/**
 * A representation of a Resource.
 */
class Resource implements JsonSerializable
{
    use DottedPropertyTrait;

    /**
     * Name of the Resource.
     *
     * @var string
     */
    protected $name;

    /**
     * The properties of the Resource.
     *
     * @var array
     */
    protected $properties = array();

    /**
     * The type of the Resource
     *
     * @var string
     */
    protected $type;

    /**
     * Set the name of the Resource.
     *
     * @param string $name
     *
     * @return \Registry\Client\Model\Resource
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the type of the Resource.
     *
     * @param string $type
     *
     * @return \Registry\Client\Model\Resource
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set the properties of the Resource.
     *
     * @param array $properties
     *
     * @return \Registry\Client\Model\Resource
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * Add a property of the Resource.
     *
     * @param string $name
     * @param number|string|array $value
     *
     * @return \Registry\Client\Model\Resource
     */
    public function addProperty($name, $value)
    {
        $this->properties[$name] = $value;

        return $this;
    }

    public function jsonSerialize()
    {
        $properties = array();
        foreach ( $this->flatten($this->properties) as $k => $v) {
            $properties[utf8_encode($k)] = utf8_encode($v);
        }
        return array(
            'name' => utf8_encode($this->name),
            'type' => utf8_encode($this->type),
            'properties' => $this->unflatten($properties),
        );
    }
}
