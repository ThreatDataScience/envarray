<?php


use PHPUnit\Framework\TestCase;
use ThreatDataScience\EnvArray\EnvArray;

class PatternTest extends TestCase
{

    public function testKeyOnlyEnvPattern()
    {
        $pattern = "{{DB_HOST}}";
        $expected = [
            'key' => 'DB_HOST',
            'type' => null,
            'default' => null
        ];

        $envar = new EnvArray();
        $this->assertEquals($expected, $envar->parseEnvPattern($pattern));
    }

    public function testKeyAndTypeEnvPatternBool()
    {
        $pattern = "{{DB_HOST:bool}}";
        $expected = [
            'key' => 'DB_HOST',
            'type' => 'bool',
            'default' => null
        ];

        $envar = new EnvArray();
        $this->assertEquals($expected, $envar->parseEnvPattern($pattern));
    }

    public function testKeyAndTypeEnvPatternString()
    {
        $pattern = "{{DB_HOST:string}}";
        $expected = [
            'key' => 'DB_HOST',
            'type' => 'string',
            'default' => null
        ];

        $envar = new EnvArray();
        $this->assertEquals($expected, $envar->parseEnvPattern($pattern));
    }

    public function testKeyAndTypeEnvPatternInt()
    {
        $pattern = "{{DB_HOST:int}}";
        $expected = [
            'key' => 'DB_HOST',
            'type' => 'int',
            'default' => null
        ];

        $envar = new EnvArray();
        $this->assertEquals($expected, $envar->parseEnvPattern($pattern));
    }

    public function testKeyAndTypeEnvPatternFloat()
    {
        $pattern = "{{DB_HOST:float}}";
        $expected = [
            'key' => 'DB_HOST',
            'type' => 'float',
            'default' => null
        ];

        $envar = new EnvArray();
        $this->assertEquals($expected, $envar->parseEnvPattern($pattern));
    }

    public function testFullEnvPatternBool()
    {
        $pattern = "{{DB_HOST:bool:true}}";
        $expected = [
            'key' => 'DB_HOST',
            'type' => 'bool',
            'default' => 'true'
        ];

        $envar = new EnvArray();
        $this->assertEquals($expected, $envar->parseEnvPattern($pattern));
    }

    public function testFullEnvPatternString()
    {
        $pattern = "{{DB_HOST:string:db01.databasehost.com}}";
        $expected = [
            'key' => 'DB_HOST',
            'type' => 'string',
            'default' => 'db01.databasehost.com'
        ];

        $envar = new EnvArray();
        $this->assertEquals($expected, $envar->parseEnvPattern($pattern));
    }

    public function testFullEnvPatternInt()
    {
        $pattern = "{{DB_PORT:int:3306}}";
        $expected = [
            'key' => 'DB_PORT',
            'type' => 'int',
            'default' => '3306'
        ];

        $envar = new EnvArray();
        $this->assertEquals($expected, $envar->parseEnvPattern($pattern));
    }

    public function testFullEnvPatternFloat()
    {
        $pattern = "{{RETRY_JITTER:float:0.34}}";
        $expected = [
            'key' => 'RETRY_JITTER',
            'type' => 'float',
            'default' => '0.34'
        ];

        $envar = new EnvArray();
        $this->assertEquals($expected, $envar->parseEnvPattern($pattern));
    }

    public function testNotAPattern(){
        $pattern = '127.0.0.1';
        $envAr = new EnvArray();
        $this->assertNull($envAr->parseEnvPattern($pattern));
    }
}
