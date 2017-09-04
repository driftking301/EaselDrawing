<?php

namespace EaselDrawing;

class Color
{
    private $red;
    private $green;
    private $blue;

    /**
     * Color constructor.
     * @param $red
     * @param $green
     * @param $blue
     */
    public function __construct(int $red, int $green, int $blue)
    {
        $this->red = max(0, min(255, $red));
        $this->green = max(0, min(255, $green));
        $this->blue = max(0, min(255, $blue));
    }

    public static function newFromArray(array $values)
    {
        $rgb = [0, 0, 0];
        $values = array_values($values);
        foreach (range(0, 2) as $index) {
            if (isset($values[$index])) {
                $rgb[$index] = $values[$index];
            }
        }
        return new Color($rgb[0], $rgb[1], $rgb[2]);
    }

    public static function newFromString(string $value)
    {
        $value = strtolower(substr($value, 0, 6));
        if (strlen($value) == 3) {
            $value = substr($value, 0, 1) . substr($value, 0, 1)
                . substr($value, 1, 1) . substr($value, 1, 1)
                . substr($value, 2, 1) . substr($value, 2, 1);
        }
        if (! preg_match('/^[[:xdigit:]]{6}$/', $value)) {
            throw new \InvalidArgumentException('A color must contain 3 or 6 hexadecimal characters');
        }
        $rgb = [];
        foreach (range(0, 2) as $index) {
            $rgb[$index] = substr($value, $index * 2, 2);
        }
        return new Color(hexdec($rgb[0]), hexdec($rgb[1]), hexdec($rgb[2]));
    }

    public function getRed(): int
    {
        return $this->red;
    }

    public function getGreen(): int
    {
        return $this->green;
    }

    public function getBlue(): int
    {
        return $this->blue;
    }

    public function getRGB()
    {
        return [$this->red, $this->blue, $this->green];
    }

    public function getHex(): string
    {
        return $this->intToHex($this->red)
            . $this->intToHex($this->green)
            . $this->intToHex($this->blue);
    }

    private function intToHex($value): string
    {
        return sprintf('%02s', dechex($value));
    }
}
