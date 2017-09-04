<?php

namespace EaselDrawing\Templates\Builders;

use EaselDrawing\Align;
use EaselDrawing\Color;
use EaselDrawing\ElementInterface;
use EaselDrawing\Elements\Label as Element;
use EaselDrawing\Font;
use EaselDrawing\Templates\Template;
use EaselDrawing\Templates\Utilities;
use EaselDrawing\TextBackground;

class Label extends AbstractBuilder
{
    /** @var string */
    private $content = '';

    /** @var Color */
    private $color;

    /** @var Font */
    private $font;

    /** @var float */
    private $fontSize = 12.0;

    /** @var Align */
    private $align;

    /** @var TextBackground */
    private $textBackground;

    public function getContent(): string
    {
        return $this->content;
    }

    public function getColor() : Color
    {
        if (null === $this->color) {
            throw new \RuntimeException("Color property has not been set");
        }
        return $this->color;
    }

    public function getFont(): Font
    {
        if (null === $this->font) {
            throw new \RuntimeException("Font property has not been set");
        }
        return $this->font;
    }

    public function getFontSize(): float
    {
        return $this->fontSize;
    }

    public function getAlign(): Align
    {
        if (null === $this->align) {
            throw new \RuntimeException("Align property has not been set");
        }
        return $this->align;
    }

    public function getTextBackground(): TextBackground
    {
        if (null === $this->textBackground) {
            throw new \RuntimeException("Text background property has not been set");
        }
        return $this->textBackground;
    }

    public function configure(array $data, Template $template)
    {
        parent::configure($data, $template);

        // content
        if (! isset($data['content']) || ! is_string($data['content'])) {
            $data['content'] = '';
        }
        $this->content = $data['content'];

        // color
        if (isset($data['color'])) {
            $this->color = Utilities::interpretColor($data['color'], $template->getForeground());
        } else {
            $this->color = $template->getForeground();
        }

        // font
        if (! isset($data['font']) || ! is_string($data['font'])) {
            $data['font'] = '';
        }
        $this->font = $template->getFont($data['font']);

        // fontSize
        if (isset($data['fontsize']) && is_numeric($data['fontsize']) && $data['fontsize'] > 0.1) {
            $this->fontSize = (float) $data['fontsize'];
        }

        // align
        if (! isset($data['align']) || ! is_string($data['align'])) {
            $data['align'] = '';
        }
        $this->align = Align::newFromString($data['align']);


        // textbackground: background
        if (isset($data['background'])) {
            $background = Utilities::interpretColor($data['background'], $template->getBackground());
        } else {
            $background = $template->getBackground();
        }
        // textbackground: backgroundtype
        if (! isset($data['backgroundtype']) || ! is_string($data['backgroundtype'])) {
            $data['backgroundtype'] = '';
        }
        $this->textBackground = TextBackground::newFromString($data['backgroundtype'], $background);
    }

    public function build(): ElementInterface
    {
        return new Element(
            $this->getX(),
            $this->getY(),
            $this->getWidth(),
            $this->getHeight(),
            $this->getContent(),
            $this->getFont(),
            $this->getFontSize(),
            $this->getColor(),
            $this->getAlign(),
            $this->getTextBackground()
        );
    }
}
