<?php

namespace EaselDrawing\Elements;

use EaselDrawing\AbstractElement;
use EaselDrawing\Color;

class Line extends AbstractElement
{
    /** @var int border weight */
    private $thickness;

    /** @var Color */
    private $color;

    /**
     * Rectangle constructor.
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     * @param Color $color
     * @param int $thickness
     */
    public function __construct(int $x, int $y, int $width, int $height, Color $color, $thickness = 1)
    {
        parent::__construct($x, $y, $width, $height);
        $this->thickness = $thickness;
        $this->color = $color;
    }

    public function getX2(): int
    {
        return $this->getX() + $this->getWidth();
    }

    public function getY2(): int
    {
        return $this->getY() + $this->getHeight();
    }

    public function getThickness(): int
    {
        return $this->thickness;
    }

    public function getColor(): Color
    {
        return $this->color;
    }
}
