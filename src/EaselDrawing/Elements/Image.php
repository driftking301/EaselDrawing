<?php

namespace EaselDrawing\Elements;

use EaselDrawing\AbstractElement;
use EaselDrawing\Color;

class Image extends AbstractElement
{
    /** @var string */
    private $filename;

    /** @var Color|null */
    private $background;

    public function __construct($x, $y, $width, $height, string $filename, Color $background = null)
    {
        parent::__construct($x, $y, $width, $height);
        $this->filename = $filename;
        $this->background = $background;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function hasBackground(): bool
    {
        return (null !== $this->background);
    }

    public function getBackground(): Color
    {
        if (! $this->hasBackground()) {
            throw new \RuntimeException('The Image does not have a background color set');
        }
        return $this->background;
    }
}
