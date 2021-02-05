<?php

test('BaseParser', function () {
    $parser = new \Tests\Component\FakeParser();
    expect($parser->getPrice())->toEqual([
        'price' => "69.99",
        'currency' => 'TL'
    ]);
});