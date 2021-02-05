<?php


namespace Softiso\PriceParser;


class N11 extends BaseParser
{
    protected function getPatterns(): array
    {
        return [
            'schema' => [
                'type' => 'Product',
                'refine' => 'schema_get_offer',
                'priceKey' => 'lowPrice',
            ]
        ];
    }
}