<?php


namespace Softiso\PriceParser;

use Softiso\PriceParser\Grabber\Grabber;
use Symfony\Component\DomCrawler\Crawler;

abstract class BaseParser implements ParserInterface
{
    protected Grabber $grabber;

    const PATTERN_METHODS = [
        'regex' => 'getByRegex',
        'schema' => 'getBySchema',
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
            return response_body($refine, null);
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

    protected function getBySchema(string $html, $schemaPattern)
    {
        $find = array_filter($this->crawler($html)->filterXPath('//script[@type="application/ld+json"]')->each(function ($node) {
            return $node->text();
        }));

        $filteredData = array_filter(array_map(function ($objStr) {
            return json_decode($objStr, true);
        }, $find), function ($obj) use ($schemaPattern){
            return $obj['@type'] === $schemaPattern['type'];
        });

        $refine = $this->refiner($schemaPattern['refine'], reset($filteredData) ?? null);

        return !is_null($refine) ? response_body($refine['price'], $refine['priceCurrency']) : null;
    }

    protected function refiner($func, $data)
    {
        if (is_null($data) || empty($data)) {
            return null;
        }

        return is_callable($func) ? $func($data) : $data;
    }
}