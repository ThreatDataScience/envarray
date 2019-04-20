<?php

namespace ThreatDataScience\EnvArray;


use InvalidArgumentException;

/**
 * Class EnvAr
 * @package ThreatDataScience\EnvAr
 * @author Andrew Breksa <andrew@threatdatascience.io>
 */
class EnvArray
{

    /**
     * A constant to represent the string type in env patterns
     */
    const TYPE_STRING = 'string';

    /**
     * A constant to represent the integer type in env patterns
     */
    const TYPE_INT = 'int';

    /**
     * A constant to represent the float type in env patterns
     */
    const TYPE_FLOAT = 'float';

    /**
     * A constant to represent the boolean type in env patterns
     */
    const TYPE_BOOL = 'bool';

    /**
     * The internal env pattern regex
     */
    const PATTERN_REGEX = '([A-Za-z0-9_\-]+)(:(?:(?:(?:string)|(?:int))|(?:(?:float)|(?:bool))))?(:((?:null)|(?:.+)))?';

    /**
     * The start string delimiter for the env pattern regex
     */
    const REGEX_DELIMITER_START_STRING = '{{';

    /**
     * The end string delimited for the env pattern regex
     */
    const REGEX_DELIMITER_END_STRING = '}}';

    /**
     * @var null|string
     */
    protected $patternRegex = null;

    /**
     * Given an associative array, fill in the env patterns as appropriate
     *
     * @param array $array
     * @return array
     */
    public function fill(array $array): array
    {
        if (Utilities::isAssoc($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    if (Utilities::isAssoc($value)) {
                        $array[$key] = $this->fill($value);
                    } else {
                        $array[$key] = $this->fillFlatArray($value);
                    }
                } else {
                    if ($this->isEnvPatternString($value)) {
                        $array[$key] = $this->convertEnvPatternString($value);
                    }
                }
            }
            return $array;
        }
        return $this->fillFlatArray($array);
    }

    /**
     * Given a flat array, fill in the env patterns as appropriate
     *
     * @param array $array
     * @return array
     */
    public function fillFlatArray(array $array): array
    {
        $index = 0;
        foreach ($array as $item) {
            if ($this->isEnvPatternString($item)) {
                $array[$index] = $this->convertEnvPatternString($item);
            }
            $index++;
        }
        return $array;
    }

    /**
     * Determine if a given string is a valid env pattern string, taking into account some specific edge cases
     *
     * @param string $string
     * @return bool
     */
    public function isEnvPatternString(string $string)
    {
        if (!(Utilities::stringStartsWith($string, $this->getDelimiterStartString()) && Utilities::stringEndsWith($string, $this->getDelimiterEndString()))) {
            return false;
        }
        foreach (['_', '-'] as $edgeCase) {
            if ($string === $this->getDelimiterStartString() . $edgeCase . $this->getDelimiterEndString()) {
                return false;
            }
        }
        if (preg_match($this->getPatternRegex(), $string, $output_array) === 1) {
            if (Utilities::stringContains($string, '_') && Utilities::stringContains($string, '-')) {
                if (preg_match('/^' . $this->getDelimiterStartString() . '[-_]+' . $this->getDelimiterEndString() . '$/', $string) === 1) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Get the start delimited of the env pattern regex/string
     *
     * @return string|null
     */
    public function getDelimiterStartString(): ?string
    {
        return self::REGEX_DELIMITER_START_STRING;
    }

    /**
     * Get the end delimiter of the env pattern regex/string
     *
     * @return string|null
     */
    public function getDelimiterEndString(): ?string
    {
        return self::REGEX_DELIMITER_END_STRING;
    }

    /**
     * Get the regex used to actually match the env pattern
     *
     * @return string
     */
    public function getPatternRegex()
    {
        return '/^' . $this->getDelimiterStartString() . self::PATTERN_REGEX . $this->getDelimiterEndString() . '$/';
    }

    /**
     * Given the env pattern array (tuple?), convert a env patter string to the proper value
     *
     * @param string $string
     * @return array|bool|false|float|int|mixed|string|null
     */
    public function convertEnvPatternString(string $string)
    {
        $data = $this->parseEnvPattern($string);
        $envValue = getenv($data['key']);
        if ($envValue === false) {
            $envValue = $data['default'];
        }
        if ($data['type'] !== null) {
            $envValue = $this->coerceEnvPatternData($data['type'], $envValue);
        }
        return $envValue;
    }

    /**
     * Parse an env pattern to the various components needed to load a env var, set a default it needed, and coerce it
     *
     * @param string $pattern
     * @return array
     */
    public function parseEnvPattern(string $pattern): ?array
    {
        $data = [
            'key' => null,
            'type' => null,
            'default' => null
        ];

        $result = preg_match($this->getPatternRegex(), $pattern, $output_array);
        if ($result === 0) {
            return null;
        }

        if (isset($output_array[3]) && strlen($output_array[3]) > 1) {
            $data['default'] = ltrim($output_array[3], ':');
        }

        if (isset($output_array[2]) && strlen($output_array[2]) > 1) {
            $data['type'] = ltrim($output_array[2], ':');
        }

        $data['key'] = $output_array[1];

        return $data;
    }

    /**
     * Given a type and string (or null), coerce the string to the proper type
     *
     * @param string $type
     * @param string|null $data
     * @return bool|float|int|string|null
     */
    public function coerceEnvPatternData(string $type, $data)
    {
        if ($data === 'null' || is_null($data)) {
            return null;
        }
        switch ($type) {
            case 'string':
                return $data;
            case 'int':
                return intval($data);
            case 'bool':
                return boolval($data);
            case 'float':
                return floatval($data);
            default:
                throw new InvalidArgumentException('Invalid coercion type passed to ' . __METHOD__);
        }
    }

}