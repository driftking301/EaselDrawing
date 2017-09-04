<?php

namespace EaselDrawingTests\Templates\Builders;

use EaselDrawing\Color;
use EaselDrawing\Elements;
use EaselDrawing\Font;
use EaselDrawing\Orientation;
use EaselDrawing\Templates\PathResolver;
use EaselDrawing\Templates\Template;
use PHPUnit\Framework\TestCase;

class BuilderTestCase extends TestCase
{
    protected function getBaseTemplate()
    {
        return new Template(
            1200,
            800,
            Color::newFromString('000066'),
            Color::newFromString('ffffff'),
            new Elements(),
            [
                new Font('DejaVu', false, false, 'dejavu.ttf'),
                new Font('DejaVuBold', true, false, 'dejavub.ttf'),
            ],
            new PathResolver('/foo/bar'),
            true,
            new Orientation(Orientation::LANDSCAPE)
        );
    }
}
