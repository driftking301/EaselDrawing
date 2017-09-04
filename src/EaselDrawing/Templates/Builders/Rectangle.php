<?php

namespace EaselDrawing\Templates\Builders;

use EaselDrawing\Color;
use EaselDrawing\ElementInterface;
use EaselDrawing\Elements\Rectangle as Element;
use EaselDrawing\Templates\Template;
use EaselDrawing\Templates\Utilities;

class Rectangle extends AbstractBuilder
{
    /** @var Color */
    private $color;
    /** @var int */
    private $thickness = 1;

    /**
     * @return Color
     */
    public function getColor()
    {
        if (null === $this->color) {
            throw new \RuntimeException("Color property has not been set");
        }
        return $this->color;
    }

    public function getThickness(): int
    {
        return $this->thickness;
    }

    public function configure(array $data, Template $template)
    {
        parent::configure($data, $template);
        if (isset($data['color'])) {
            $this->color = Utilities::interpretColor($data['color'], $template->getForeground());
        } else {
            $this->color = $template->getForeground();
        }
        if (isset($data['thickness']) && is_integer($data['thickness']) && $data['thickness'] > 0) {
            $this->thickness = $data['thickness'];
        }
    }

    public function build(): ElementInterface
    {
        return new Element(
            $this->getX(),
            $this->getY(),
            $this->getWidth(),
            $this->getHeight(),
            $this->getColor(),
            $this->getThickness()
        );
    }
}
