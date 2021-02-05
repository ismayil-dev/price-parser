<?php


namespace  Tests\Component;


use Softiso\PriceParser\BaseParser;

class FakeParser extends BaseParser
{

    public function getPrice()
    {
        return $this->parsePrice(static::fakeHtmlData());
    }

    protected function getPatterns(): array
    {
        return [
            'schema' => [
                'type' => 'Product',
                'refine' => 'schema_get_offer',
                'priceKey' => 'price',
            ],
            'meta' => [
                [
                    'name' => "@name='twitter:data1'",
                    'refine' => 'dot_replacer',
                ]
            ],
        ];
    }

    public static function fakeHtmlData()
    {
        return file_get_contents(__DIR__ . '/parse.html');
    }
}