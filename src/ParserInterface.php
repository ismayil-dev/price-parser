<?php


namespace Softiso\PriceParser;


interface ParserInterface
{
    /**
     * @param string $url
     * @return mixed
     */
    public function url(string $url);

    /**
     * @return mixed
     */
    public function getPrice();
}