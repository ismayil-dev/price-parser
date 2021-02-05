<?php

require_once '../vendor/autoload.php';

use Softiso\PriceParser\Defacto;

$url = 'https://www.defacto.com.tr/kapsonlu-sisme-mont-1579240';

$parser = new Defacto();

var_dump($parser->url($url)->getPrice());