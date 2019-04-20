<?php


use PHPUnit\Framework\TestCase;
use ThreatDataScience\EnvArray\EnvArray;

class TypeCoercionTest extends TestCase
{

    public function testString()
    {
        $envAr = new EnvArray();
        $this->assertIsString($envAr->coerceEnvPatternData(EnvArray::TYPE_STRING, '123'));
    }

    public function testInt()
    {
        $envAr = new EnvArray();
        $this->assertIsInt($envAr->coerceEnvPatternData(EnvArray::TYPE_INT, '123'));
    }

    public function testBool()
    {
        $envAr = new EnvArray();
        $this->assertIsBool($envAr->coerceEnvPatternData(EnvArray::TYPE_BOOL, 'true'));
        $this->assertIsBool($envAr->coerceEnvPatternData(EnvArray::TYPE_BOOL, 'false'));
    }

    public function testFloat()
    {
        $envAr = new EnvArray();
        $this->assertIsFloat($envAr->coerceEnvPatternData(EnvArray::TYPE_FLOAT, '123.456'));
    }

    public function testNull()
    {
        $envAr = new EnvArray();
        $this->assertNull($envAr->coerceEnvPatternData(EnvArray::TYPE_FLOAT, 'null'));
        $this->assertNull($envAr->coerceEnvPatternData(EnvArray::TYPE_FLOAT, null));
    }

    public function testException()
    {
        $envAr = new EnvArray();
        $this->expectException(InvalidArgumentException::class);
        $envAr->coerceEnvPatternData('foo', '123.456');
    }

}
