<?php

require 'vendor/autoload.php';

//use \Registry\Client\Client ;

$client = new \Registry\Client\Client('username', 'password', 'accountName', 'storeName', 'http://registry.linkorb.com');

$resourceKey = 'example';

// Check if a resource key exists
if ($client->resourceExists($resourceKey)) {
   echo "Exists...\n";
} else {
   echo "Does not exists...\n";
}

// Setting some properties
$properties =  ['hello'=>'world', 'color'=>'green'];
$client->setProperties($resourceKey, $properties);

$eventName = 'login';
$properties = ['browser'=>'firefox'];

$client->addEvent($resourceKey, $eventName, $properties);

// Get properties
$res = $client->getProperties($resourceKey);
var_dump($res) ;

