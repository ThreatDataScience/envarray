<?php


use PHPUnit\Framework\TestCase;
use ThreatDataScience\EnvArray\EnvArray;

class ConvertTest extends TestCase
{

    public function testNoValue()
    {
        $pattern = '{{DB_HOST:string}}';
        $envAr = new EnvArray();
        $this->assertNull($envAr->convertEnvPatternString($pattern));
    }

    public function testNoValueWithDefault()
    {
        $pattern = '{{DB_HOST:string:127.0.0.1}}';
        $envAr = new EnvArray();
        $value = $envAr->convertEnvPatternString($pattern);
        $this->assertEquals('127.0.0.1', $value);
        $this->assertIsString($value);
    }

    public function testNoValueAndNoType()
    {
        $pattern = '{{DB_HOST}}';
        $envAr = new EnvArray();
        $this->assertNull($envAr->convertEnvPatternString($pattern));
    }

    public function testWithValue()
    {
        $expected = 'mysql';
        $pattern = '{{DB_HOST:string}}';
        $envAr = new EnvArray();
        putenv('DB_HOST=' . $expected);
        $this->assertEquals($expected, $envAr->convertEnvPatternString($pattern));
    }

    public function testWithValueAndType()
    {
        $expected = '3306';
        $pattern = '{{DB_PORT:int:3307}}';
        $envAr = new EnvArray();
        putenv('DB_PORT=' . $expected);
        $value = $envAr->convertEnvPatternString($pattern);
        $this->assertEquals($expected, $value);
        $this->assertIsInt($value);
    }
}