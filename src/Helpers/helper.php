<?php

function dot_replacer(string $str)
{
    return str_replace(',', '.', $str);
}

function price_delimiter(string $str)
{
    $resp = explode(' ', $str);
    return !empty($resp) ? response_body($resp[0], $resp[1]) : null;
}

function response_body($price = null, $currency = null) {
    return ['price' => $price, 'currency' => $currency];
}
