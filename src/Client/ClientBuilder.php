<?php

namespace Registry\Client;

use GuzzleHttp\Client;

class ClientBuilder
{
    protected $config = array();

    private $api_path = '/api';
    private $api_version = 'v1';

    /**
     * ClientBuilder is constructed with an array of configuration options.
     * For example:
     *     $builder = new ClientBuilder(array(
     *         'api_host' => 'some-hostname',
     *         'auth' => array('myusername', 's3kr3t')
     *     ));
     *
     * @see \Registry\Client\ClientOptions  and
     * @see \GuzzleHttp\RequestOptions      for the options available.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->processBaseUri();
    }

    /**
     * Build an API client.
     *
     * @return GuzzleHttp\ClientInterface
     */
    public function build()
    {
        return new Client($this->config);
    }

    /**
     * Get the configured base URI.
     *
     * @return string
     */
    public function getBaseUri()
    {
        if (! isset($this->config[ClientOptions::BASE_URI])) {
            return '';
        }

        return $this->config[ClientOptions::BASE_URI];
    }

    /**
     * Configure the builder with a base URI.
     *
     * @param string $base_uri
     */
    public function setBaseUri($base_uri)
    {
        $this->config[ClientOptions::BASE_URI] = $base_uri;

        return $this;
    }

    private function processBaseUri()
    {
        if (isset($this->config[ClientOptions::BASE_URI])) {
            return;
        }

        $base_uri = 'http';
        if (isset($this->config[ClientOptions::SECURE_HTTP])
            && $this->config[ClientOptions::SECURE_HTTP] !== false
        ) {
            $base_uri .= 's';
        }
        $base_uri .= '://';

        if (isset($this->config[ClientOptions::API_HOST])) {
            $base_uri .= $this->config[ClientOptions::API_HOST];
        } else {
            $base_uri .= 'localhost';
        }

        $base_uri .= $this->api_path . '/';

        if (isset($this->config[ClientOptions::API_VERSION])) {
            $base_uri .= $this->config[ClientOptions::API_VERSION];
        } else {
            $base_uri .= $this->api_version;
        }

        $this->config[ClientOptions::BASE_URI] = $base_uri . '/';
    }
}