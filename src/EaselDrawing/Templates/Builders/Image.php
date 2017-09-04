<?php

namespace EaselDrawing\Templates\Builders;

use EaselDrawing\Color;
use EaselDrawing\ElementInterface;
use EaselDrawing\Elements\Image as Element;
use EaselDrawing\Templates\Template;
use EaselDrawing\Templates\Utilities;

class Image extends AbstractBuilder
{
    /** @var string */
    private $filename = '';
    /** @var Color|null */
    private $background;

    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return Color|null
     */
    public function getBackground()
    {
        return $this->background;
    }

    public function configure(array $data, Template $template)
    {
        parent::configure($data, $template);

        // filename
        if (! isset($data['file']) || ! is_string($data['file'])) {
            $data['file'] = '';
        }
        $this->filename = $template->getPathResolver()->obtainPath($data['file']);
        // background (remember that background is optional)
        if (isset($data['background'])) {
            $this->background = Utilities::interpretColor($data['background'], $template->getBackground());
        }
    }

    public function build(): ElementInterface
    {
        return new Element(
            $this->getX(),
            $this->getY(),
            $this->getWidth(),
            $this->getHeight(),
            $this->getFilename(),
            $this->getBackground()
        );
    }
}
