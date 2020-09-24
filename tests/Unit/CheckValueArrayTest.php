<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CheckValueArrayTest extends TestCase
{
    public function test_success_array(): void
    {
        $response = array_must_same($this->data(), [
            'initial_price',
            'selling_price'
        ], 0);

        $this->assertTrue($response);
    }

    public function test_error_index_array(): void
    {
        $response = array_must_same($this->data(), [
            'initial_price',
            'selling_price',
            'key3'
        ], 0);

        $this->assertFalse($response);
    }

    public function test_return_false(): void
    {
        $response = array_must_same($this->data(), [
            'initial_price',
            'selling_price',
        ], 5);

        $this->assertFalse($response);
    }



    private function data(): array
    {
        return [
            'initial_price' => 0,
            'selling_price' => 0,
        ];
    }

}
