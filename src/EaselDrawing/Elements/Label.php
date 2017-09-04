<?php

namespace EaselDrawing\Elements;

use EaselDrawing\AbstractElement;
use EaselDrawing\Align;
use EaselDrawing\Color;
use EaselDrawing\Font;
use EaselDrawing\TextBackground;

class Label extends AbstractElement
{
    /** @var string */
    private $content;
    /** @var Font */
    private $font;
    /** @var float */
    private $size;
    /** @var Color */
    private $color;
    /** @var Align */
    private $align;
    /** @var TextBackground */
    private $textBackground;

    public function __construct(
        int $x,
        int $y,
        int $width,
        int $height,
        string $content,
        Font $font,
        float $size,
        Color $color,
        Align $align = null,
        TextBackground $textBackground = null
    ) {
        parent::__construct($x, $y, $width, $height);
        $this->setContent($content);
        $this->setFont($font);
        $this->setSize($size);
        $this->setColor($color);
        $this->setAlign($align ? : new Align(Align::LEFT));
        $this->setTextBackground($textBackground ? : new TextBackground(TextBackground::BOX, new Color(255, 255, 255)));
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getFont(): Font
    {
        return $this->font;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function getAlign(): Align
    {
        return $this->align;
    }

    public function getTextBackground(): TextBackground
    {
        return $this->textBackground;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setFont(Font $font)
    {
        $this->font = $font;
    }

    public function setSize(float $size)
    {
        $this->size = $size;
    }

    public function setColor(Color $color)
    {
        $this->color = $color;
    }

    public function setAlign(Align $align)
    {
        $this->align = $align;
    }

    public function setTextBackground(TextBackground $textBackground)
    {
        $this->textBackground = $textBackground;
    }
}
