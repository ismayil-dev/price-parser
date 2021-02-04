<?php


namespace Softiso\PriceParser;


class Trendyol extends BaseParser
{

    protected function getByRegex(string $html, $regexPatterns)
    {
        foreach ($regexPatterns as $pattern) {
            $find = array_filter($this->crawler($html)->filterXPath('//script96')->each(function ($node) use ($pattern) {
                if (str_contains($node->text(), '__PRODUCT_DETAIL_APP_INITIAL_STATE__')) {
                    preg_match($pattern['pattern'], utf8_decode($node->text()), $out);
                    return $this->refiner($pattern['refine'], $out[$pattern['index']]);
                }
            }));

            return !empty($find) ? reset($find)['product']['price'] : null;
        }

        return null;
    }

    protected function getPatterns()
    {
        return [
            'regex' => [
                [
                    'pattern' => '/=(.*?)};/',
                    'index' => 1,
                    'refine' => function ($content) {
                        return json_decode(trim($content . '}'), true);
                    },
                ]
            ],
            'meta' => [
                [
                    'name' => "@name='twitter:data1'",
                    'refine' => 'dot_replacer',
                ],
                [
                    'name' => "@name='twitter:data2'",
                    'refine' => 'dot_replacer',
                ],
            ],
            'class' => [
                [
                    'className' => '.prc-slg',
                    'refine' => function (string $str) {
                        $resp = explode(' ', $str);
                        return $this->responseBody($resp[0], $resp[1]);
                    },
                ],
            ]
        ];
    }
}