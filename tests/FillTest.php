<?php


use PHPUnit\Framework\TestCase;
use ThreatDataScience\EnvArray\EnvArray;

class FillTest extends TestCase
{
    public function testNoPatternsFlatArray()
    {
        $array = [
            'DB_HOST'
        ];
        $envAr = new EnvArray();
        $this->assertEquals($array, $envAr->fill($array));
    }

    public function testWithPatternsFlatArray()
    {
        putenv('DB_HOST=mysql');
        $array = [
            '{{DB_HOST}}'
        ];
        $envAr = new EnvArray();
        $this->assertEquals([
            'mysql'
        ], $envAr->fill($array));
    }

    public function testWithAndWithoutPatternsFlatArray()
    {
        putenv('DB_HOST=mysql');
        $array = [
            '{{DB_HOST}}',
            'DB_PORT'
        ];
        $envAr = new EnvArray();
        $this->assertEquals([
            'mysql',
            'DB_PORT'
        ], $envAr->fill($array));
    }

    public function testWithoutPatternsAssocArray()
    {
        $envAr = new EnvArray();
        $array = [
            'database' => [
                'host' => '127.0.0.1'
            ]
        ];
        $this->assertEquals($array, $envAr->fill($array));
    }

    public function testWithPatternsAssocArray()
    {
        putenv('DB_HOST=mysql');
        $envAr = new EnvArray();
        $array = [
            'database' => [
                'host' => '{{DB_HOST:string:mysql}}'
            ]
        ];
        $this->assertEquals([
            'database' => [
                'host' => 'mysql'
            ]
        ], $envAr->fill($array));
    }

    public function testWithAndWithoutPatternsAssocArray()
    {
        putenv('DB_HOST=mysql');
        $envAr = new EnvArray();
        $array = [
            'database' => [
                'host' => '{{DB_HOST:string:mysql}}',
                'port' => '3306'
            ]
        ];
        $this->assertEquals([
            'database' => [
                'host' => 'mysql',
                'port' => '3306'
            ]
        ], $envAr->fill($array));
    }

    public function testWithAndWithoutPatternsAssocWithFlatArray()
    {
        putenv('DB_HOST=mysql');
        $envAr = new EnvArray();
        $array = [
            'database' => [
                'host' => [
                    '{{DB_HOST}}',
                    '127.0.0.1'
                ],
                'port' => '3306',
            ]
        ];
        $this->assertEquals([
            'database' => [
                'host' => [
                    'mysql',
                    '127.0.0.1'
                ],
                'port' => '3306'
            ]
        ], $envAr->fill($array));
    }

}