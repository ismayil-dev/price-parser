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

    protected function extractFromMeta(string $html, array $metaPatterns, $extract = array('content'))
    {
        foreach ($metaPatterns as $pattern) {
            $find = $this->crawler($html)->filterXPath("//meta[" . $pattern['name'] . "]")->extract($extract);

            if (empty($find) || is_null($find)) {
                continue;
            }
            return $this->refiner($pattern['refine'], reset($find));
        }

        return null;
    }

    protected function extractByClassName(string $html, array $classPatterns)
    {
        foreach ($classPatterns as $pattern) {
            $find = $this->crawler($html)->filter($pattern['className'])->text();

            if (is_null($find) || is_null($find)) {
                continue;
            }

            return $this->refiner($pattern['refine'], $find);
        }

        return null;
    }

    protected function responseBody($price = null, $currency = null)
    {
        return ['price' => $price, 'currency' => $currency];
    }

    protected function refiner($func, $data)
    {
        if (is_callable($func)) {
            return $func($data);
        }

        return $data;
    }
}