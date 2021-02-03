<?php


namespace Softiso\PriceParser;

use GuzzleHttp\Client;

class HtmlGetter
{
    protected string $url = "";
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
        return $this->client->get($this->url);
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