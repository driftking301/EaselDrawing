<?php

namespace EaselDrawing;

class Canvas
{
    /** @var int */
    private $width;
    /** @var int */
    private $height;
    /** @var Elements */
    private $elements;
    /** @var Color */
    private $background;
    /** @var bool */
    private $grayScale;
    /** @var Orientation */
    private $orientation;

    public function __construct(
        int $width,
        int $height,
        Color $background = null,
        Elements $elements = null,
        bool $grayScale = false,
        Orientation $orientation = null
    ) {
        $this->width = $width;
        $this->height = $height;
        $this->elements = $elements ? : new Elements();
        $this->background = $background ? : new Color(255, 255, 255);
        $this->grayScale = $grayScale;
        $this->orientation = $orientation ? : new Orientation(Orientation::LANDSCAPE);
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getElements(): Elements
    {
        return $this->elements;
    }

    public function getBackground(): Color
    {
        return $this->background;
    }

    public function isGrayScale(): bool
    {
        return $this->grayScale;
    }

    public function getOrientation(): Orientation
    {
        return $this->orientation;
    }
}
