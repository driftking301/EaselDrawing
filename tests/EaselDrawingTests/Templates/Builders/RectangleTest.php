<?php

namespace EaselDrawingTests\Templates\Builders;

use EaselDrawing\Elements;
use EaselDrawing\Templates\Builders\Rectangle;

class RectangleTest extends BuilderTestCase
{
    public function testConstruct()
    {
        $object = new Rectangle();
        $this->assertEquals(0, $object->getX());
        $this->assertEquals(0, $object->getY());
        $this->assertEquals(1, $object->getWidth());
        $this->assertEquals(1, $object->getHeight());
        $this->assertEquals(1, $object->getThickness());
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Rectangle::class, Rectangle::create());
    }

    public function testNewObjectThrowsExceptionOnGetColor()
    {
        $object = new Rectangle();
        $this->expectException(\RuntimeException::class);

        $object->getColor();
    }

    public function testConfigureWithoutData()
    {
        $template = $this->getBaseTemplate();
        $object = new Rectangle();
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
        $object = new Rectangle();
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

        /** @var Elements\Rectangle $rectangle */
        $rectangle = $object->build();
        $this->assertInstanceOf(Elements\Rectangle::class, $rectangle);

        $this->assertEquals($object->getX(), $rectangle->getX());
        $this->assertEquals($object->getY(), $rectangle->getY());
        $this->assertEquals($object->getWidth(), $rectangle->getWidth());
        $this->assertEquals($object->getHeight(), $rectangle->getHeight());
        $this->assertEquals($object->getThickness(), $rectangle->getThickness());
        $this->assertEquals($object->getColor(), $rectangle->getColor());
    }
}
