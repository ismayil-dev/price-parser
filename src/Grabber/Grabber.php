<?php


namespace Softiso\PriceParser\Grabber;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Grabber
{
    private string $url = "";
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public static function url(string $url)
    {
        return (new self())->setUrl($url);
    }

    private function setUrl(string $url)
    {
        $this->url = $url;
        return $this;
    }

    private function makeRequest()
    {
        try {
            return $this->client->get($this->url);
        } catch (GuzzleException $e) {
            return $e->getMessage();
        }
    }

    public function getBody()
    {
        return $this->makeRequest()->getBody();
    }

    public function getHeader()
    {
        return $this->makeRequest()->getHeaders();
    }
}