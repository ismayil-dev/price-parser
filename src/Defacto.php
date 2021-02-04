<?php


namespace Softiso\PriceParser;


class Defacto extends BaseParser
{
    public function getPrice()
    {
        $html = $this->getContent();
        return $this->extractPriceFromBody($html);
    }

    protected function extractPriceFromBody($html)
    {
        $price = $this->extractFromMeta($html, $this->getPatterns()['meta']);
        $meta = $this->crawler($html)->filterXPath("//meta[@name='twitter:data1']")->extract(array('content'));
        dd($meta);
        $class = $this->crawler($html)->filter(".prc-slg")->text();
        $js = $this->crawler($html)->filterXPath('//script')->each(function ($node) {
            if (str_contains($node->text(), '__PRODUCT_DETAIL_APP_INITIAL_STATE__')) {
                preg_match('/=(.*?)};/', utf8_decode($node->text()), $out);
                dd(json_decode(trim($out[1] . '}'), true));

            }
        });
//        return $html;
    }

    protected function extractPriceFromHeader($html)
    {
        return $html;
    }

    protected function getPatterns()
    {
        return [
            'regex' => [
                [
                    'pattern' => '/=(.*?)};/',
                    'index' => 1,
                    'refine' => function ($content) {
                        return trim($content . '"}}}');
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