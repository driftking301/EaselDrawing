<?php

namespace EaselDrawing\Templates;

use EaselDrawing\Color;
use EaselDrawing\Elements;
use EaselDrawing\Orientation;
use Symfony\Component\Yaml\Yaml;

class TemplateFactory
{
    /** @var string */
    private $relativePath;
    /** @var ElementFactory */
    private $elementFactory;

    public function __construct(string $relativePath, ElementFactory $elementFactory = null)
    {
        $this->relativePath = $relativePath;
        $this->elementFactory = $elementFactory ? : $this->getDefaultElementFactory();
    }

    public function getDefaultElementFactory()
    {
        $elementFactory = new ElementFactory();
        $elementFactory->register('line', Builders\Line::class);
        $elementFactory->register('rectangle', Builders\Rectangle::class);
        $elementFactory->register('image', Builders\Image::class);
        $elementFactory->register('label', Builders\Label::class);
        return $elementFactory;
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function getElementFactory(): ElementFactory
    {
        return $this->elementFactory;
    }

    public function setRelativePath(string $relativePath)
    {
        $this->relativePath = $relativePath;
    }

    public function obtain(string $contents, string $format): Template
    {
        // retrieve data from contents
        $data = $this->retrieveData($contents, $format);

        // path resolver
        $pathResolver = new PathResolver($this->relativePath);

        // width
        if (! isset($data['width']) || ! is_integer($data['width']) || $data['width'] < 1) {
            $data['width'] = 800;
        }
        $width = $data['width'];

        // grayscale
        if (! isset($data['grayscale']) || ! is_bool($data['grayscale'])) {
            $data['grayscale'] = false;
        }
        $grayscale = $data['grayscale'];

        // orientation
        if (! isset($data['orientation']) || ! is_string($data['orientation'])) {
            $data['orientation'] = Orientation::LANDSCAPE;
        }
        $orientation = Utilities::interpretOrientation($data['orientation']);

        // height
        if (! isset($data['height']) || ! is_integer($data['height']) || $data['height'] < 1) {
            $data['height'] = 600;
        }
        $height = $data['height'];

        // foreground
        if (! isset($data['foreground'])) {
            $data['foreground'] = false;
        }
        $foreground = Utilities::interpretColor($data['foreground'], Color::newFromString('000000'));

        // background
        if (! isset($data['background'])) {
            $data['background'] = false;
        }
        $background = Utilities::interpretColor($data['background'], Color::newFromString('ffffff'));

        // fonts
        if (! isset($data['fonts']) || ! is_array($data['fonts'])) {
            $data['fonts'] = [];
        }
        $fonts = [];
        foreach ($data['fonts'] as $name => $sourceFont) {
            $fonts[] = Utilities::interpretFont($name, $sourceFont, $pathResolver);
        }

        // template
        $elements = new Elements();
        $template = new Template(
            $width,
            $height,
            $foreground,
            $background,
            $elements,
            $fonts,
            $pathResolver,
            $grayscale,
            $orientation
        );

        // elements
        $elementFactory = $this->getElementFactory();
        if (! isset($data['elements']) || ! is_array($data['elements'])) {
            $data['elements'] = [];
        }
        foreach ($data['elements'] as $index => $sourceElement) {
            if (! is_array($sourceElement) || count($sourceElement) !== 1) {
                throw new \InvalidArgumentException("The element index $index is not valid");
            }
            foreach ($sourceElement as $type => $dataElement) {
                $elements->add($elementFactory->element($type, $dataElement, $template));
            }
        }

        return $template;
    }

    public function obtainFromFile(string $filename, string $format): Template
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            throw new \InvalidArgumentException("File $filename does not exists or is not readable");
        }
        return $this->obtain(file_get_contents($filename), $format);
    }

    public function retrieveData(string $contents, string $format): array
    {
        $format = strtoupper($format);
        if ('YAML' === $format) {
            return Yaml::parse($contents) ? : [];
        }
        if ('JSON' === $format) {
            return json_decode($contents, true) ? : [];
        }
        throw new \InvalidArgumentException("Format $format is not recognized");
    }
}
