<?php


namespace Softiso\PriceParser\Grabber;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Softiso\PriceParser\Exceptions\NotFoundException;

class Grabber
{
    /**
     * @var string
     */
    private string $url = "";
    /**
     * @var Client
     */
    private Client $client;

    /**
     * Grabber constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param string $url
     * @return Grabber
     */
    public static function url(string $url): Grabber
    {
        return (new self())->setUrl($url);
    }

    /**
     * @param string $url
     * @return Grabber
     */
    private function setUrl(string $url): Grabber
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return ResponseInterface|string
     */
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

    /**
     * @return string
     * @throws NotFoundException
     */
    public function getBody(): string
    {
        $request = $this->makeRequest();

        if (!$request instanceof Response) throw new NotFoundException("404 not found");

        return $request->getBody()->getContents();
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->makeRequest()->getHeaders();
    }
}