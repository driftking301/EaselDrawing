<?php

namespace EaselDrawing\Templates;

use EaselDrawing\Canvas;
use EaselDrawing\Color;
use EaselDrawing\Elements;
use EaselDrawing\Font;
use EaselDrawing\Orientation;

class Template
{
    /** @var int */
    private $width;

    /** @var int */
    private $height;

    /** @var Color */
    private $foreground;

    /** @var Color */
    private $background;

    /** @var Font[] */
    private $fonts;

    /** @var PathResolver */
    private $pathResolver;

    /** @var Elements */
    private $elements;

    /** @var bool */
    private $grayScale;

    /** @var Orientation */
    private $orientation;

    /**
     * Template constructor.
     * @param int $width
     * @param int $height
     * @param Color $foreground
     * @param Color $background
     * @param Elements $elements
     * @param Font[] $fonts
     * @param PathResolver $pathResolver
     * @param bool $grayScale,
     * @param Orientation $orientation
     */
    public function __construct(
        int $width,
        int $height,
        Color $foreground,
        Color $background,
        Elements $elements,
        array $fonts,
        PathResolver $pathResolver,
        bool $grayScale,
        Orientation $orientation
    ) {
        if ($width < 1) {
            throw new \InvalidArgumentException("Width must be an integer greater than zero");
        }
        if ($height < 1) {
            throw new \InvalidArgumentException("Height must be an integer greater than zero");
        }
        foreach ($fonts as $index => $font) {
            if (! ($font instanceof Font)) {
                throw new \InvalidArgumentException("The font index $index is not valid");
            }
        }
        $this->width = $width;
        $this->height = $height;
        $this->foreground = $foreground;
        $this->background = $background;
        $this->fonts = $fonts;
        $this->pathResolver = $pathResolver;
        $this->elements = $elements;
        $this->grayScale = $grayScale;
        $this->orientation = $orientation;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getForeground(): Color
    {
        return $this->foreground;
    }

    public function getBackground(): Color
    {
        return $this->background;
    }

    /**
     * @return Font[]
     */
    public function getFonts(): array
    {
        return $this->fonts;
    }

    public function getPathResolver(): PathResolver
    {
        return $this->pathResolver;
    }

    public function getElements(): Elements
    {
        return $this->elements;
    }

    public function isGrayScale(): bool
    {
        return $this->grayScale;
    }

    public function getOrientation(): Orientation
    {
        return $this->orientation;
    }

    public function getFont(string $name): Font
    {
        if (! count($this->fonts)) {
            throw new \RuntimeException('There are no fonts in the template');
        }
        foreach ($this->fonts as $font) {
            if (0 === strcasecmp($font->getFamily(), $name)) {
                return $font;
            }
        }
        return $this->fonts[0];
    }

    public function asCanvas() : Canvas
    {
        return new Canvas(
            $this->getWidth(),
            $this->getHeight(),
            $this->getBackground(),
            $this->getElements(),
            $this->isGrayScale(),
            $this->getOrientation()
        );
    }
}
