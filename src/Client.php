<?php

namespace Registry\Client;

class Client {
    const ENDPOINT = 'http://registry.linkorb.com' ;

    protected $username ;
    protected $password ;
    protected $accountName ;
    protected $storeName ;
    protected $apiUrl ;
    
    private $fullUrl ;
    private $client ;

    public function __construct($username, $password, $accountName, $storeName, $apiUrl = '')
    {
        $this->username = $username ;
        $this->password = $password ;
        $this->accountName = $accountName ;
        $this->storeName = $storeName ;
        if($apiUrl == '') {
            $this->apiUrl = self::ENDPOINT ;
        } else {
            $this->apiUrl = $apiUrl ;
        }
        $fullUrl = $this->apiUrl.'/api/v1/'.$this->accountName.'/'.$this->storeName.'/' ;
        $this->client = new \GuzzleHttp\Client([
            'auth' => [$this->username, $this->password], 
            'base_uri' => $fullUrl, 
            'http_errors' => false]);
    }

    public function resourceExists($resourceKey) {
        return ($this->getProperties($resourceKey) !== false) ;
    }

    public function setProperties($resourceKey, $properties) {
        try {
            $reply = $this->client->get("p?_p=".$resourceKey."&".http_build_query($properties));
            $code = $reply->getStatusCode() ; 
        } catch(\Exception $e) { } ;
        return ($code == 200) ;
    }

    public function getProperties($resourceKey) {
        $res = false ;
        try {
            $reply = $this->client->get("r/".$resourceKey);
            $code = $reply->getStatusCode() ;
        } catch(\Exception $e) { } ;
        if ($code == 200) {
            $res = json_decode($reply->getBody()->getContents(), true) ;
        }
        return $res ;
    }

    public function addEvent($resourceKey, $eventName, $properties) {
        try {
            $reply = $this->client->get("e?_r=".$resourceKey."&_e=".$eventName."&".http_build_query($properties));
            $code = $reply->getStatusCode() ;
        } catch(\Exception $e) { } ;
        return ($code == 200) ;
    }
    
}

