<?php


use PHPUnit\Framework\TestCase;
use ThreatDataScience\EnvArray\EnvArray;

class IsPatternTest extends TestCase
{

    public function testNotPatternStart()
    {
        $envAr = new EnvArray();
        $pattern = '{{localhost';
        $this->assertFalse($envAr->isEnvPatternString($pattern));
    }

    public function testNotPatternEnd()
    {
        $envAr = new EnvArray();
        $pattern = 'localhost}}';
        $this->assertFalse($envAr->isEnvPatternString($pattern));
    }

    public function testNotPattern()
    {
        $envAr = new EnvArray();
        $pattern = 'localhost';
        $this->assertFalse($envAr->isEnvPatternString($pattern));
    }

    public function testNotPatternBadChars()
    {
        $envAr = new EnvArray();
        $invalidChars = [
            '!',
            '@',
            '`',
            '"',
            '<',
            '>',
            '/',
            '\\',
            ':',
            ';',
            '#',
            '$',
            '%',
            '^',
            '&',
            '*',
            '(',
            ')',
            '+',
            '=',
            "'",
            '.',
            ',',
            '?'
        ];
        foreach ($invalidChars as $item) {
            $pattern = '{{' . $item . 'localhost}}';
            $this->assertFalse($envAr->isEnvPatternString($pattern), 'Failed testing that the key value cannot start with ' . $item);
            $pattern = '{{localhost' . $item . '}}';
            $this->assertFalse($envAr->isEnvPatternString($pattern), 'Failed testing that the key value cannot end with ' . $item);
            $pattern = '{{' . $item . '}}';
            $this->assertFalse($envAr->isEnvPatternString($pattern), 'Failed testing that the key cannot be ' . $item);
        }

        $edgeCases = [
            '-',
            '_',
        ];

        foreach ($edgeCases as $edgeCase) {
            $pattern = '{{' . $edgeCase . '}}';
            $this->assertFalse($envAr->isEnvPatternString($pattern));
        }
    }

    public function testCrazySpecificEdgeCase()
    {
        $envAr = new EnvArray();
        $pattern = '{{' . str_repeat('-', rand(1, 5)) . str_repeat('_', rand(1, 5)) . str_repeat('-', rand(1, 5)) . '}}';
        $this->assertFalse($envAr->isEnvPatternString($pattern));
        $pattern = '{{' . str_repeat('_', rand(1, 5)) . str_repeat('-', rand(1, 5)) . str_repeat('_', rand(1, 5)) . '}}';
        $this->assertFalse($envAr->isEnvPatternString($pattern));
    }

    public function testIsPattern()
    {
        $envAr = new EnvArray();
        $pattern = '{{LOCALHOST}}';
        $this->assertTrue($envAr->isEnvPatternString($pattern));

        $pattern = '{{_LOCAL-HOST_}}';
        $this->assertTrue($envAr->isEnvPatternString($pattern));
    }

    public function testIsPatternWithType()
    {
        $envAr = new EnvArray();
        $pattern = '{{_LOCAL-HOST_:string}}';
        $this->assertTrue($envAr->isEnvPatternString($pattern));
    }

    public function testIsPatternWithTypeAndDefaultMissing()
    {
        $envAr = new EnvArray();
        $pattern = '{{_LOCAL-HOST_:string:}}';
        $this->assertTrue($envAr->isEnvPatternString($pattern));
    }

    public function testIsPatternWithTypeAndDefaultPresent()
    {
        $envAr = new EnvArray();
        $pattern = '{{_LOCAL-HOST_:string:127.0.0.1}}';
        $this->assertTrue($envAr->isEnvPatternString($pattern));
    }

}