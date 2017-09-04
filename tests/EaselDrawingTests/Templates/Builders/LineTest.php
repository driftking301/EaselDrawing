<?php

namespace EaselDrawingTests\Templates\Builders;

use EaselDrawing\Elements;
use EaselDrawing\Templates\Builders\Line;

class LineTest extends BuilderTestCase
{
    public function testConstruct()
    {
        $object = new Line();
        $this->assertEquals(0, $object->getX());
        $this->assertEquals(0, $object->getY());
        $this->assertEquals(1, $object->getWidth());
        $this->assertEquals(1, $object->getHeight());
        $this->assertEquals(1, $object->getThickness());
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Line::class, Line::create());
    }

    public function testNewObjectThrowsExceptionOnGetColor()
    {
        $object = new Line();
        $this->expectException(\RuntimeException::class);

        $object->getColor();
    }

    public function testConfigureWithoutData()
    {
        $template = $this->getBaseTemplate();
        $object = new Line();
        $object->configure([], $template);

        $this->assertEquals(0, $object->getX());
        $this->assertEquals(0, $object->getY());
        $this->assertEquals(1, $object->getWidth());
        $this->assertEquals(1, $object->getHeight());
        $this->assertEquals(1, $object->getThickness());
        $this->assertSame($template->getForeground(), $object->getColor());
    }

    public function testConfigureWithPositionValid()
    {
        $template = $this->getBaseTemplate();
        $object = new Line();
        $object->configure([
            'position' => [10, 20, 30],
            'size' => [100, 200, 300],
            'color' => '666666',
            'thickness' => 2,
        ], $template);

        $this->assertEquals(10, $object->getX());
        $this->assertEquals(20, $object->getY());
        $this->assertEquals(100, $object->getWidth());
        $this->assertEquals(200, $object->getHeight());
        $this->assertEquals(2, $object->getThickness());
        $this->assertEquals('666666', $object->getColor()->getHex());

        /** @var Elements\Line $line */
        $line = $object->build();
        $this->assertInstanceOf(Elements\Line::class, $line);

        $this->assertEquals($object->getX(), $line->getX());
        $this->assertEquals($object->getY(), $line->getY());
        $this->assertEquals($object->getWidth(), $line->getWidth());
        $this->assertEquals($object->getHeight(), $line->getHeight());
        $this->assertEquals($object->getThickness(), $line->getThickness());
        $this->assertEquals($object->getColor(), $line->getColor());
    }
}
