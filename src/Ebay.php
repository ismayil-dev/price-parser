<?php


namespace Softiso\PriceParser;

//WebPage
class Ebay extends BaseParser
{
    protected function getPatterns()
    {
        return [
            'schema' => [
                'type' => 'WebPage',
                'refine' => function($arr) {
                   if (empty($arr)){
                       return null;
                   }
                   return schema_get_offer(reset(schema_get_offer($arr['mainEntity'])['itemOffered']));
                },
                'priceKey' => 'price',
            ]
        ];
    }
}