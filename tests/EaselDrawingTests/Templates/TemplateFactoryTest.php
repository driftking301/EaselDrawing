<?php

namespace EaselDrawingTests\Templates;

use EaselDrawing\Color;
use EaselDrawing\Elements;
use EaselDrawing\Font;
use EaselDrawing\Orientation;
use EaselDrawing\Templates\Template;
use EaselDrawing\Templates\TemplateFactory;
use PHPUnit\Framework\TestCase;

class TemplateFactoryTest extends TestCase
{
    public function testConstruct()
    {
        $factory = new TemplateFactory('/foo');
        $this->assertEquals('/foo', $factory->getRelativePath());
    }

    public function testRetrieveDataWithInvalidFormat()
    {
        $factory = new TemplateFactory('/foo');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Format INVALID is not recognized');
        $factory->retrieveData('', 'invalid');
    }

    public function testRetrieveDataJsonWithNoContent()
    {
        $factory = new TemplateFactory('/foo');
        $data = $factory->retrieveData('', 'json');
        $this->assertEquals([], $data);
    }

    public function testRetrieveDataJsonWithContent()
    {
        $factory = new TemplateFactory('/foo');
        $data = $factory->retrieveData('{"a": 1, "b": 2}', 'json');
        $this->assertEquals(['a' => 1, 'b' => 2], $data);
    }

    public function testRetrieveDataYamlWithNoContent()
    {
        $factory = new TemplateFactory('/foo');
        $data = $factory->retrieveData('', 'yaml');
        $this->assertEquals([], $data);
    }

    public function testRetrieveDataYamlWithContent()
    {
        $factory = new TemplateFactory('/foo');
        $data = $factory->retrieveData("a: 1\nb: 2", 'yaml');
        $this->assertEquals(['a' => 1, 'b' => 2], $data);
    }

    public function testObtainWithNoContent()
    {
        $factory = new TemplateFactory('/foo');

        $template = $factory->obtain('', 'json');
        $this->assertInstanceOf(Template::class, $template);
        $this->assertEquals(800, $template->getWidth());
        $this->assertEquals(600, $template->getHeight());
        $this->assertInstanceOf(Elements::class, $template->getElements());
        $this->assertCount(0, $template->getElements());
        $this->assertEquals([], $template->getFonts());
        $this->assertInstanceOf(Color::class, $template->getForeground());
        $this->assertEquals(Color::newFromString('000000'), $template->getForeground());
        $this->assertInstanceOf(Color::class, $template->getBackground());
        $this->assertEquals(Color::newFromString('ffffff'), $template->getBackground());
    }

    public function testObtainWithContent()
    {
        $values = [
            'width' => 1000,
            'height' => 500,
            'foreground' => [255, 255, 255],
            'background' => '000000',
            'grayscale' => true,
            'orientation' => 'portrait',
            'fonts' => [
                'Arial' => '/fonts/foo.ttf',
                'ArialBold' => [
                    'bold' => true,
                    'italic' => false,
                    'location' => '/fonts/bar.ttf',
                ],
            ],
            'elements' => [
                [
                    'rectangle' => [
                        'position' => [10, 20],
                        'size' => [100, 200],
                    ],
                ],
                [
                    'line' => [
                        'position' => [20, 10],
                        'size' => [200, 100],
                        'thickness' => 4
                    ],
                ],
            ],
        ];
        $content = json_encode($values);
        $factory = new TemplateFactory('/foo');
        $foreground = Color::newFromString('ffffff');
        $background = Color::newFromString('000000');
        $expectedElements = [
            new Elements\Rectangle(10, 20, 100, 200, $foreground),
            new Elements\Line(20, 10, 200, 100, $foreground, 4),
        ];
        $expectedFonts = [
            new Font('Arial', false, false, '/fonts/foo.ttf'),
            new Font('ArialBold', true, false, '/fonts/bar.ttf'),
        ];

        $template = $factory->obtain($content, 'json');
        $this->assertInstanceOf(Template::class, $template);
        $this->assertEquals(1000, $template->getWidth());
        $this->assertEquals(500, $template->getHeight());
        $this->assertTrue($template->isGrayScale());
        $this->assertEquals(Orientation::PORTRAIT, $template->getOrientation()->getValue());
        $this->assertCount(2, $template->getElements());
        foreach ($template->getElements() as $index => $element) {
            $this->assertEquals($element, $expectedElements[$index]);
        }
        $this->assertCount(2, $template->getFonts());
        foreach ($template->getFonts() as $index => $font) {
            $this->assertEquals($font, $expectedFonts[$index]);
        }
        $this->assertEquals($foreground, $template->getForeground());
        $this->assertEquals($background, $template->getBackground());
    }
}
