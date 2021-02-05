<?php

/**
 * @param string $str
 * @return string|string[]
 */
function dot_replacer(string $str)
{
    return str_replace(',', '.', $str);
}

/**
 * @param string $str
 * @return null[]|null
 */
function price_delimiter(string $str): ?array
{
    $resp = explode(' ', $str);
    return !empty($resp) ? response_body($resp[0], $resp[1]) : null;
}

/**
 * @param null $price
 * @param null $currency
 * @return array|null[]
 */
function response_body($price = null, $currency = null): array
{
    return ['price' => $price, 'currency' => $currency];
}

/**
 * @param array $arr
 * @return mixed|null
 */
function schema_get_offer(array $arr)
{
    return array_key_exists('offers', $arr) ? $arr['offers'] : null;
}