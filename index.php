<?php

use Softiso\PriceParser\Trendyol;

require_once 'vendor/autoload.php';

$url = 'https://www.trendyol.com/bershka/erkek-kum-rengi-kapusonlu-sweatshirt-p-55752533?boutiqueId=551482&merchantId=104961';
//$url = 'https://www.trendyol.com/bayefendi/kapusonlu-sweatshirt-mood-p-70089784';

$parser  = new Trendyol();
echo $parser->url($url)->getPrice();