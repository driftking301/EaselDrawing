<?php

namespace EaselDrawing\Templates\Builders;

use EaselDrawing\Templates\Template;
use EaselDrawing\Templates\ElementBuilderInterface;

abstract class AbstractBuilder implements ElementBuilderInterface
{
    /** @var int */
    private $x = 0;
    /** @var int */
    private $y = 0;
    /** @var int */
    private $width = 1;
    /** @var int */
    private $height = 1;

    public static function create(): ElementBuilderInterface
    {
        return new static();
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

    public function configure(array $data, Template $template)
    {
        $this->setPosition($data);
        $this->setSize($data);
    }

    protected function setPosition(array $data)
    {
        $position = [$this->getX(), $this->getY()];
        if (isset($data['position']) && is_array($data['position']) && count($data['position']) >= 2) {
            $position = $data['position'];
        }
        if (is_integer($position[0])) {
            $this->x = $position[0];
        }
        if (is_integer($position[1])) {
            $this->y = $position[1];
        }
    }

    protected function setSize(array $data)
    {
        $size = [$this->getWidth(), $this->getHeight()];
        if (isset($data['size']) && is_array($data['size']) && count($data['size']) >= 2) {
            $size = $data['size'];
        }
        if (is_integer($size[0]) && $size[0] > 0) {
            $this->width = $size[0];
        }
        if (is_integer($size[1]) && $size[1] > 0) {
            $this->height = $size[1];
        }
        return $this;
    }
}
