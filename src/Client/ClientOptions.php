<?php

namespace Registry\Client;

class ClientOptions
{
    /**
     * The base URI of the API endpoints.
     */
    const BASE_URI = 'base_uri';

    /**
     * Request Handler
     * @var callable
     * @see \GuzzleHttp\Client::__construct
     */
    const HANDLER = 'handler';

    /**
     * Set this to true to communicate with the API over HTTPS.
     */
    const SECURE_HTTP = 'secure';

    /**
     * Version of the API, for example: 'v1'.
     */
    const API_VERSION = 'api_version';

    /**
     * The name and optional port number of the API host.
     */
    const API_HOST = 'api_host';
}
