<?php

require 'vendor/autoload.php';

use \Registry\Client\ClientBuilder;
use \Registry\Client\Store;

// Set up the Store
$config = array(
    'api_host' => 'registry.linkorb.nl',
    'auth' => array('myusername', 'mypassword'),
    'secure' => true,
);
$store = new Store(new ClientBuilder($config), 'myaccount', 'mystore');

// Use the store to create an event
$event = $store->createEvent('test.event', null, array('severity' => 'error'));

// Use the store to create a resource
$resource = $store->createResource('myappname', 'app', array('env' => 'prod', 'uptime' => 7));
$event->addResource($resource, 'primary.environment');

// save the event and associated resource
$response = $event->save();

// process the response
$result = json_decode((string) $response->getBody(), true);
if ($result['success']) {
    echo 'Success!';
} else {
    printf('Fail: "%s".', $result['error']['message']);
}
