<?php

namespace EaselDrawing;

class Align
{
    const LEFT = 'LEFT';
    const CENTER = 'CENTER';
    const RIGHT = 'RIGHT';

    private $value;

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function setValue(string $value)
    {
        if (! in_array($value, [static::LEFT, static::CENTER, static::RIGHT])) {
            throw new \InvalidArgumentException('Align value is not valid');
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
        if (! in_array($value, [static::LEFT, static::CENTER, static::RIGHT])) {
            $value = static::LEFT;
        }
        return new Align($value);
    }
}
