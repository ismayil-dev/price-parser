<?php

require_once '../vendor/autoload.php';

use Softiso\PriceParser\Defacto;

$html = file_get_contents(__DIR__.'/example.html');

$parser = new Defacto();

var_dump($parser->html($html)->getPrice());