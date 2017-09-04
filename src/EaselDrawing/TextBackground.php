<?php

namespace EaselDrawing;

class TextBackground
{
    const NONE = 'NONE';
    const FIT = 'FIT';
    const BOX = 'BOX';

    /** @var string */
    private $value;
    /** @var Color */
    private $color;

    public function __construct(string $value, Color $color)
    {
        $this->setValue($value);
        $this->setColor($color);
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function setValue(string $value)
    {
        if (! in_array($value, [static::NONE, static::FIT, static::BOX])) {
            throw new \InvalidArgumentException('TextBackground value is not valid');
        }
        $this->value = $value;
    }

    public function setColor(Color $color)
    {
        $this->color = $color;
    }

    public static function newFromString($value, Color $color)
    {
        $value = strtoupper($value);
        if (! in_array($value, [static::NONE, static::FIT, static::BOX])) {
            $value = static::BOX;
        }
        return new static($value, $color);
    }
}
