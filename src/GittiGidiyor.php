<?php


namespace Softiso\PriceParser;

class GittiGidiyor extends BaseParser
{
    /**
     * @return array[]
     */
    protected function getPatterns(): array
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