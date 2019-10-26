EnvArray
--------
[![Build Status](https://travis-ci.org/ThreatDataScience/envarray.svg?branch=master)](https://travis-ci.org/ThreatDataScience/envarray)
[![codecov](https://codecov.io/gh/ThreatDataScience/envarray/branch/master/graph/badge.svg)](https://codecov.io/gh/ThreatDataScience/envarray)

# Summary
EnvArray allows you to auto-magically fill array values with env vars. It's zero-dependency as well (outside of PHPUnit 
for development/your sanity).

# Usage

```php
<?php

/**
 * Given the following environmental vars:
 * 
 * DB_HOST=mysql.myhost.com
 * DB_USER=webmaster
 * DB_PASS=weakpassword
 */

$envArray = new \ThreatDataScience\EnvArray\EnvArray();
$array = [
    'database' => [
        'host' => '{{DB_HOST:string:127.0.0.1}}',
        'port' => '{{DB_PORT:int:3306}}',
        'user' => '{{DB_USER:string:root}}',
        'password' => '{{DB_PASS:string:root}}'
    ]
];
$array = $envArray->fill($array);

/**
 * Will give you:
 * 
 *  [
 *    'database' => [
 *      'host' => 'mysql.myhost.com',
 *      'port' => 3306,
 *      'user' => 'webmaster',
 *      'password' => 'weakpassword'
 *    ]
 *  ];
 */

/**
 * It works with flat arrays too!
 * 
 * (Note, we don't condone this specific use case example, use a load balancer)
 * 
 * Given the following environmental vars:
 * 
 * ELASTIC_01=01.es.myhost.com
 * ELASTIC_01=02.es.myhost.com
 * ELASTIC_03=03.es.myhost.com
 */

$envArray = new \ThreatDataScience\EnvArray\EnvArray();
$array = [
    'elastic' => [
        '{{ELASTIC_01}}',
        '{{ELASTIC_02}}',
        '{{ELASTIC_03}}'
    ]
];
$array = $envArray->fill($array);

/**
 * Will give you:
 * 
 *  [
 *    'elastic' => [
 *       '01.es.myhost.com',
 *       '02.es.myhost.com',
 *       '03.es.myhost.com',
 *     ]
 *  ];
 */

```

# Env String Pattern
```text
{{<env var name>:<coercion type>:<default value>}}
```

## Env Var Name
Env var names can be any combination of `[A-Za-z0-9_-]`, however they cannot be _only_ `[-_]+`.

## Coercion Type
Supported types:

- Boolean via `bool`
- Integer via `int`
- Float via `float`
- String via `string` (or no type, but useful as you need to define the type if you want to use a default value)

## Default Values
Any value goes, given the limitation that there are no sanity checks for something like:
```text
{{DB_HOST:int:789.0909}}
```
### Default default
The default default is `null`.

## Why not use `\${.+}`?
Using `\${.+}` introduces too many conflicts, as there are reasonable use cases for passing an env var as a literal in a
string. We agree that it would make things simpler from a basic use-case perspective, but the level of complexity of the
project jumps like crazy if we need to support denoting that we want to _keep_ the literal string vs. parsing it.

# "Hacking on the source"
Code is in `./src`, and tests are in `./tests`. 

We welcome contributions, suggestions, and bug reports, however we do ask that if you open a ticket, please be as 
verbose as possible to keep things streamlined. We also maintain 100% code coverage, so PR's with tests are awesome. 