<?php

namespace EaselDrawingTests\Templates\Builders;

use EaselDrawing\Align;
use EaselDrawing\Color;
use EaselDrawing\Elements;
use EaselDrawing\Templates\Builders\Label;
use EaselDrawing\TextBackground;

class LabelTest extends BuilderTestCase
{
    public function testConstruct()
    {
        $object = new Label();
        $this->assertEquals(0, $object->getX());
        $this->assertEquals(0, $object->getY());
        $this->assertEquals(1, $object->getWidth());
        $this->assertEquals(1, $object->getHeight());
        $this->assertEquals('', $object->getContent());
        $this->assertEquals(12.0, $object->getFontSize());
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Label::class, Label::create());
    }

    public function testNewObjectThrowsExceptionOnGetColor()
    {
        $object = new Label();
        $this->expectException(\RuntimeException::class);

        $object->getColor();
    }

    public function testNewObjectThrowsExceptionOnGetAlign()
    {
        $object = new Label();
        $this->expectException(\RuntimeException::class);

        $object->getAlign();
    }

    public function testNewObjectThrowsExceptionOnGetTextBackground()
    {
        $object = new Label();
        $this->expectException(\RuntimeException::class);

        $object->getTextBackground();
    }

    public function testNewObjectThrowsExceptionOnGetFont()
    {
        $object = new Label();
        $this->expectException(\RuntimeException::class);

        $object->getFont();
    }

    public function testConfigureWithoutData()
    {
        $template = $this->getBaseTemplate();
        $object = new Label();
        $object->configure([], $template);
        $expectedAlign = new Align(Align::LEFT);
        $expectedTextBackground = new TextBackground(TextBackground::BOX, $template->getBackground());

        $this->assertEquals(0, $object->getX());
        $this->assertEquals(0, $object->getY());
        $this->assertEquals(1, $object->getWidth());
        $this->assertEquals(1, $object->getHeight());
        $this->assertEquals('', $object->getContent());
        $this->assertSame($template->getFont(''), $object->getFont());
        $this->assertEquals(12.0, $object->getFontSize());
        $this->assertSame($template->getForeground(), $object->getColor());
        $this->assertEquals($expectedAlign, $object->getAlign());
        $this->assertEquals($expectedTextBackground, $object->getTextBackground());
    }

    public function testConfigureWithPositionValid()
    {
        $template = $this->getBaseTemplate();
        $object = new Label();
        $object->configure([
            'position' => [10, 20, 30],
            'size' => [100, 200, 300],
            'content' => 'This is Sparta!',
            'font' => 'DejaVuBold',
            'fontsize' => '42',
            'color' => '666666',
            'align' => 'center',
            'background' => [128, 64, 32],
            'backgroundtype' => 'fit',
        ], $template);
        $expectedColor = Color::newFromString('666666');
        $expectedFont = $template->getFont('DejaVuBold');
        $expectedAlign = new Align(Align::CENTER);
        $expectedTextBackground = new TextBackground(TextBackground::FIT, new Color(128, 64, 32));

        $this->assertEquals(10, $object->getX());
        $this->assertEquals(20, $object->getY());
        $this->assertEquals(100, $object->getWidth());
        $this->assertEquals(200, $object->getHeight());
        $this->assertEquals('This is Sparta!', $object->getContent());
        $this->assertSame($expectedFont, $object->getFont());
        $this->assertEquals(42, $object->getFontSize());
        $this->assertEquals($expectedColor, $object->getColor());
        $this->assertEquals($expectedAlign, $object->getAlign());
        $this->assertEquals($expectedTextBackground, $object->getTextBackground());

        /** @var Elements\Label $label */
        $label = $object->build();
        $this->assertInstanceOf(Elements\Label::class, $label);

        $this->assertEquals($object->getX(), $label->getX());
        $this->assertEquals($object->getY(), $label->getY());
        $this->assertEquals($object->getWidth(), $label->getWidth());
        $this->assertEquals($object->getHeight(), $label->getHeight());
        $this->assertEquals($object->getContent(), $label->getContent());
        $this->assertSame($object->getFont(), $object->getFont());
        $this->assertEquals($object->getFontSize(), $object->getFontSize());
        $this->assertSame($object->getColor(), $label->getColor());
        $this->assertSame($object->getAlign(), $object->getAlign());
        $this->assertSame($object->getTextBackground(), $object->getTextBackground());
    }
}
