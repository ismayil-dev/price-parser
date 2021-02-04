<?php


namespace Softiso\PriceParser;


class Defacto extends BaseParser
{

    protected function getPatterns()
    {
        return [
            'schema' => [
                'type' => 'Product',
                'refine' => fn(array $arr) => array_key_exists('offers', $arr) ? $arr['offers'] : null,
            ],
            'class' => [
                [
                    'className' => '.product-info-prices-new',
                    'refine' => 'price_delimiter',
                ],
                [
                    'className' => '.product-info-prices-basket-sale',
                    'refine' => 'price_delimiter',
                ],
                [
                    'className' => '.product-info-prices-basket-inline-new',
                    'refine' => 'price_delimiter',
                ],
                [
                    'className' => '.product-info-prices-basket-inline-old',
                    'refine' => 'price_delimiter',
                ],
            ]
        ];
    }
}