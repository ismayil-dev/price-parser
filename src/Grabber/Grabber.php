<?php


namespace Softiso\PriceParser\Grabber;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Softiso\PriceParser\Exceptions\NotFoundException;

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
            return $this->client->get($this->url,  [
                'headers' => [
                    'User-Agent' => $_SERVER['HTTP_USER_AGENT']
                ]
            ]);
        } catch (GuzzleException $e) {
            return $e->getMessage();
        }
    }

    public function getBody()
    {
        $request = $this->makeRequest();

        if (!$request instanceof Response) throw new NotFoundException("404 not found");

        return $request->getBody()->getContents();
    }

    public function getHeader()
    {
        return $this->makeRequest()->getHeaders();
    }
}