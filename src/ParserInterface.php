<?php


namespace Softiso\PriceParser;


interface ParserInterface
{
    public function url(string $url);

    public function getPrice();
}