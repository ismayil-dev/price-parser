<?php


namespace Softiso\PriceParser;

use Softiso\PriceParser\Grabber\Grabber;
use Symfony\Component\DomCrawler\Crawler;

class BaseParser implements ParserInterface
{
    protected Grabber $grabber;

    protected const PATTERN_METHODS = [
        'regex' => 'getByRegex',
        'schema' => 'getBySchema',
        'meta' => 'getByMeta',
        'class' => 'getByClass',
    ];

    /**
     * BaseParser constructor.
     */
    public function __construct()
    {
        if (function_exists('boot')) {
            $this->boot();
        }
    }

    /**
     * @param string $url
     * @return $this
     */
    public function url(string $url): BaseParser
    {
        return self::setGrabber(Grabber::url($url));
    }

    /**
     * @return null
     * @throws Exceptions\NotFoundException
     */
    public function getPrice()
    {
        $html = $this->grabber->getBody();

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

    /**
     * @param Grabber $grabber
     * @return $this
     */
    protected function setGrabber(Grabber $grabber): BaseParser
    {
        $this->grabber = $grabber;
        return $this;
    }

    /**
     * @param $html
     * @return Crawler
     */
    protected function crawler($html): Crawler
    {
        return new Crawler($html);
    }

    /**
     * @return array
     */
    protected function getPatterns(): array
    {
        return [];
    }

    /**
     * @param string $html
     * @param array $metaPatterns
     * @param string[] $extract
     * @return array|null
     */
    protected function getByMeta(string $html, array $metaPatterns, $extract = array('content')): ?array
    {
        foreach ($metaPatterns as $pattern) {
            $find = $this->crawler($html)->filterXPath("//meta[" . $pattern['name'] . "]")->extract($extract);
            $refine = $this->refiner($pattern['refine'], reset($find) ?? null);
            return response_body($refine, null);
        }

        return null;
    }

    /**
     * @param string $html
     * @param array $classPatterns
     * @return null
     */
    protected function getByClass(string $html, array $classPatterns)
    {
        foreach ($classPatterns as $pattern) {
            $find = $this->crawler($html)->filter($pattern['className'])->text();
            return $this->refiner($pattern['refine'], $find);
        }

        return null;
    }

    /**
     * @param string $html
     * @param $schemaPattern
     * @return array|null
     */
    protected function getBySchema(string $html, $schemaPattern): ?array
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

        return !is_null($refine) ? response_body($refine[$schemaPattern['priceKey']], $refine['priceCurrency']) : null;
    }

    /**
     * @param $func
     * @param $data
     * @return null
     */
    protected function refiner($func, $data)
    {
        if (is_null($data) || empty($data)) {
            return null;
        }

        return is_callable($func) ? $func($data) : $data;
    }
}