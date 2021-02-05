<?php


namespace Softiso\PriceParser;


class Defacto extends BaseParser
{

    /**
     * @return array
     */
    protected function getPatterns(): array
    {
        return [
            'schema' => [
                'type' => 'Product',
                'refine' => 'schema_get_offer',
                'priceKey' => 'price',
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