<?php


namespace Softiso\PriceParser;


use Softiso\PriceParser\Grabber\Grabber;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseParser implements ParserInterface
{
    protected Grabber $grabber;

    public function __construct()
    {
        if (function_exists('boot')) {
            $this->boot();
        }
    }

    public function url(string $url)
    {
        return self::setGrabber(Grabber::url($url));
    }

    public abstract function getPrice();

    protected function setGrabber(Grabber $grabber)
    {
        $this->grabber = $grabber;
        return $this;
    }

    protected function getContent()
    {
        return $this->grabber->getBody()->getContents();
    }

    protected function getHeaders()
    {
        return $this->grabber->getHeader();
    }

    public function crawler($html)
    {
        return new Crawler($html);
    }

    protected function getPatterns()
    {
        return [];
    }
}