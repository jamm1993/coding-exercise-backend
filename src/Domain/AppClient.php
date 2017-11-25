<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain;

class AppClient
{
    /*
     * @var string
     */
    private $protocol = "http://";
    /*
     * @var string
     */
    private $domain = "";
    /*
     * @var Client
     */
    private $client = null;
    /*
     * @var config
     */
    private $config = [];

    public function __construct()
    {
       $this->protocol = "http://";
       $this->domain = "";
       $this->client = null;
       $this->config = [];
        
    }
    
    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @throws \Exception
     */
    public function setDomain(string $domain)
    {
        if (filter_var(gethostbyname($domain), FILTER_VALIDATE_IP)) {
            $this->setDomain($this->getProtocol().$domain);
        } else {
            $this->getLogger()->error("Invalid Domain", ["domain" => $domain]);
            throw new \Exception($message, 500);
        }
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string|int $key
     * @return mixed|null
     */
    public function getConfigKey($key)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        return null;
    }

    /**
     * @param string|int $key
     * @param mixed $value
     */
    public function addConfigKey($key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * @param string|int $key
     * @return bool
     */
    public function delConfigKey($key)
    {
        if (isset($this->config[$key])) {
            unset($this->config[$key]);
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     */
    public function setProtocol(string $protocol)
    {
        $this->protocol = $protocol;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
      return $this->client;
    }  

    /**
     * @param Client $client
     */
    public function setClient(Client $client)
    {
      $this->client = $client;
    }
    
    public function initClient(Client $client = null)
    {
        if (!empty($this->getDomain())) {
            $this->addConfigKey("base_uri", $this->getDomain());
        }
        if (is_null($client)) {
            $this->setClient(new Client($this->getConfig()));
        } else {
            $this->setClient($client);
        }
    }    

    public function request(string $tags, string $search, int $page) 
    {
        try {
            $response = $this->getClient()->request(
                "GET",
                "/api/?q=$tags&i=$search&p=$page"
            );
        } catch(\Exception $e) {
            $message = sprintf($this->getDomain()." unable to connect to server");
            $this->getLogger()->error($message, ["domain" => $this->getDomain(), "error" => $e->getMessage()]);
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        return $response->getBody()->getContents();
    }
}

