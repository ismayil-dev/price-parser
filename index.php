<?php

use Softiso\PriceParser\N11;

require_once 'vendor/autoload.php';

//$url = 'https://www.trendyol.com/bershka/erkek-kum-rengi-kapusonlu-sweatshirt-p-55752533?boutiqueId=551482&merchantId=104961';
//$url = 'https://www.trendyol.com/izla/sir-agda-makine-2-adet-pudrali-soyulabilen-boncuk-agda-spatula-seti-97854253214521-p-34417675';
$url = 'https://www.trendyol.com/kumtel/blackthree-3-lu-ankastre-set-b66-s2-firin-ko-410-bf-ocak-da6-833-davlumbaz-p-63038917';

//$url = 'https://urun.n11.com/sony-playstation-4/ps4-pes-2021-turkce-ps5-uyumlu-sifir-seritli-ps4-pes-21-P448524514';

$parser = new \Softiso\PriceParser\Trendyol();

dd($parser->url($url)->getPrice());