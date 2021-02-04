<?php


namespace Softiso\PriceParser;

use Softiso\PriceParser\Grabber\Grabber;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseParser implements ParserInterface
{
    protected Grabber $grabber;

    const PATTERN_METHODS = [
        'regex' => 'getByRegex',
        'meta' => 'getByMeta',
        'class' => 'getByClass',
    ];

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

    public function getPrice()
    {
        $html = $this->getContent();

        foreach (self::PATTERN_METHODS as $key => $method) {
            if (key_exists($key, $this->getPatterns()) && method_exists($this, $method)) {
                $find = $this->$method($html, $this->getPatterns()[$key]);
                if (!is_null($find)) {
                    return $find;
                }
            }
        }

        return null;
    }

    protected function setGrabber(Grabber $grabber)
    {
        $this->grabber = $grabber;
        return $this;
    }

    protected function getContent()
    {
        return $this->grabber->getBody()->getContents();
    }

    protected function crawler($html)
    {
        return new Crawler($html);
    }

    protected function getPatterns()
    {
        return [];
    }

    protected function getByMeta(string $html, array $metaPatterns, $extract = array('content'))
    {
        foreach ($metaPatterns as $pattern) {
            $find = $this->crawler($html)->filterXPath("//meta[" . $pattern['name'] . "]")->extract($extract);
            $refine = $this->refiner($pattern['refine'], reset($find) ?? null);
            return $this->responseBody($refine, null);
        }

        return null;
    }

    protected function getByClass(string $html, array $classPatterns)
    {
        foreach ($classPatterns as $pattern) {
            $find = $this->crawler($html)->filter($pattern['className'])->text();
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
        if (is_null($data) || empty($data)) {
            return null;
        }

        return is_callable($func) ? $func($data) : $data;
    }
}