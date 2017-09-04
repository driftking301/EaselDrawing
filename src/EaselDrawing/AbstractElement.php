<?php

namespace EaselDrawing;

abstract class AbstractElement implements ElementInterface
{
    /** @var string */
    private $id = '';

    /** @var int x-axis position */
    private $x = 0;

    /** @var int x-axis position */
    private $y = 0;

    /** @var int width of the element */
    private $width;

    /** @var int height of the element */
    private $height;

    public function __construct(int $x, int $y, int $width, int $height)
    {
        $this->setX($x);
        $this->setY($y);
        $this->setWidth($width);
        $this->setHeight($height);
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function setX(int $x)
    {
        $this->x = $x;
    }

    public function setY(int $y)
    {
        $this->y = $y;
    }

    public function setWidth(int $width)
    {
        $this->width = $width;
    }

    public function setHeight(int $height)
    {
        $this->height = $height;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getX(): int
    {
        return $this->x;
    }

    public function getY(): int
    {
        return $this->y;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}
