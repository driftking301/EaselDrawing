<?php

namespace EaselDrawing;

class Orientation
{
    const LANDSCAPE = 'LANDSCAPE';
    const PORTRAIT = 'PORTRAIT';

    private $value;

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function setValue(string $value)
    {
        if (! in_array($value, [static::LANDSCAPE, static::PORTRAIT])) {
            throw new \InvalidArgumentException('Orientation value is not valid');
        }
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function newFromString(string $value)
    {
        $value = strtoupper($value);
        if (! in_array($value, [static::LANDSCAPE, static::PORTRAIT])) {
            $value = static::LANDSCAPE;
        }
        return new Orientation($value);
    }
}
