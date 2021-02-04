<?php


namespace Softiso\PriceParser;


class Trendyol extends BaseParser
{
    public function getPrice()
    {
        $html = $this->getContent();

        $findRegex = $this->extractByRegex($html, $this->getPatterns()['regex']);
        $findByMeta = $this->extractFromMeta($html, $this->getPatterns()['meta']);
        $findByClass = $this->extractByClassName($html, $this->getPatterns()['class']);

        return !is_null($findRegex) ? $findRegex : (!is_null($findByMeta) ? $findByMeta : $findByClass);
    }

    protected function extractByRegex(string $html, $regexPatterns)
    {
        foreach ($regexPatterns as $pattern) {
            $find = $this->crawler($html)->filterXPath('//script')->each(function ($node) use ($pattern) {
                if (str_contains($node->text(), '__PRODUCT_DETAIL_APP_INITIAL_STATE__')) {
                    preg_match($pattern['pattern'], utf8_decode($node->text()), $out);
                    return $this->refiner($pattern['refine'], $out[$pattern['index']]);
                }
                return  null;
            });

            $find = array_filter($find);

            if (empty($find)) {
                return null;
            }

            return reset($find)['product'];
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
                        return explode(' ', $str);
                    },
                ],
            ]
        ];
    }
}