<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class SumNumberTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $result = sumTwoNumbers(10 , 20);

        $this->assertEquals(30, $result);
    }
}
