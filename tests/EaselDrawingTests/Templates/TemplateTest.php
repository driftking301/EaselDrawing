<?php

namespace EaselDrawingTests\Templates;

use EaselDrawing\Color;
use EaselDrawing\Elements;
use EaselDrawing\Font;
use EaselDrawing\Orientation;
use EaselDrawing\Templates\PathResolver;
use EaselDrawing\Templates\Template;
use EaselDrawingTests\Templates\Builders\BuilderTestCase;

class TemplateTest extends BuilderTestCase
{
    public function testConstructor()
    {
        $width = 100;
        $height = 200;
        $foreground = Color::newFromString('ffffff');
        $background = Color::newFromString('000000');
        $elements = new Elements();
        $fonts = [
            new Font('DejaVu', false, false, 'dejavu.ttf'),
            new Font('DejaVuBold', true, false, 'dejavub.ttf'),
        ];
        $pathResolver = new PathResolver();
        $grayScale = true;
        $orientation = new Orientation(Orientation::LANDSCAPE);
        $template = new Template(
            $width,
            $height,
            $foreground,
            $background,
            $elements,
            $fonts,
            $pathResolver,
            $grayScale,
            $orientation
        );
        $this->assertEquals($width, $template->getWidth());
        $this->assertEquals($height, $template->getHeight());
        $this->assertSame($foreground, $template->getForeground());
        $this->assertSame($background, $template->getBackground());
        $this->assertSame($elements, $template->getElements());
        $this->assertEquals($fonts, $template->getFonts());
        $this->assertSame($pathResolver, $template->getPathResolver());
        $this->assertEquals($grayScale, $template->isGrayScale());
        $this->assertSame($orientation, $template->getOrientation());
    }

    public function testGetFontWithCoincidence()
    {
        $template = $this->getBaseTemplate();

        $fonts = $template->getFonts();
        foreach ($fonts as $font) {
            $this->assertSame($font, $template->getFont(strtolower($font->getFamily())));
        }
    }

    public function testGetFontWithNoCoincidence()
    {
        $template = $this->getBaseTemplate();
        $fonts = $template->getFonts();
        $this->assertSame($fonts[0], $template->getFont('non-existent'));
    }

    public function testGetFontFailsIfTemplateDoesNotHaveAnyFonts()
    {
        $color = Color::newFromString('ffffff');
        $elements = new Elements();
        $fonts = [];
        $pathResolver = new PathResolver();
        $template = new Template(
            100,
            200,
            $color,
            $color,
            $elements,
            $fonts,
            $pathResolver,
            true,
            new Orientation(Orientation::LANDSCAPE)
        );

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('There are no fonts in the template');
        $template->getFont('');
    }

    public function testAsCanvas()
    {
        $template = $this->getBaseTemplate();

        $canvas = $template->asCanvas();
        $this->assertEquals($template->getWidth(), $canvas->getWidth());
        $this->assertEquals($template->getHeight(), $canvas->getHeight());
        $this->assertSame($template->getBackground(), $canvas->getBackground());
        $this->assertSame($template->getElements(), $canvas->getElements());
        $this->assertEquals($template->isGrayScale(), $canvas->isGrayScale());
        $this->assertSame($template->getOrientation(), $canvas->getOrientation());
    }
}
