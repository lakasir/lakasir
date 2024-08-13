<?php

test('method.selling_method is normal', function () {
    config(['method.selling_method' => 'normal']);
    $this->assertEquals('normal', config('method.selling_method'));
});

test('method.selling_method is lifo', function () {
    config(['method.selling_method' => 'lifo']);
    $this->assertEquals('lifo', config('method.selling_method'));
});

test('method.selling_method is fifo', function () {
    config(['method.selling_method' => 'fifo']);
    $this->assertEquals('fifo', config('method.selling_method'));
});
