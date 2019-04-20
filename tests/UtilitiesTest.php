<?php


use PHPUnit\Framework\TestCase;
use ThreatDataScience\EnvArray\Utilities;

class UtilitiesTest extends TestCase
{

    public function testEmptyEndsWith()
    {
        $this->assertTrue(Utilities::stringEndsWith('foobar', ''));
    }

}