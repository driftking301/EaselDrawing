<?php

namespace EaselDrawingTests\Templates\Builders;

use EaselDrawing\Elements;
use EaselDrawing\Templates\Builders\Image;

class ImageTest extends BuilderTestCase
{
    public function testConstruct()
    {
        $object = new Image();
        $this->assertEquals(0, $object->getX());
        $this->assertEquals(0, $object->getY());
        $this->assertEquals(1, $object->getWidth());
        $this->assertEquals(1, $object->getHeight());
        $this->assertEquals('', $object->getFilename());
        $this->assertNull($object->getBackground());
    }

    public function testCreate()
    {
        $this->assertInstanceOf(Image::class, Image::create());
    }

    public function testConfigureWithoutData()
    {
        $template = $this->getBaseTemplate();
        $object = new Image();
        $object->configure([], $template);

        $this->assertEquals(0, $object->getX());
        $this->assertEquals(0, $object->getY());
        $this->assertEquals(1, $object->getWidth());
        $this->assertEquals(1, $object->getHeight());
        $this->assertEquals($template->getPathResolver()->obtainPath(''), $object->getFilename());
        $this->assertNull($object->getBackground());
    }

    public function testConfigureWithPositionValid()
    {
        $template = $this->getBaseTemplate();
        $object = new Image();
        $object->configure([
            'position' => [10, 20, 30],
            'size' => [100, 200, 300],
            'background' => '666666',
            'file' => '/somefile',
        ], $template);

        $this->assertEquals(10, $object->getX());
        $this->assertEquals(20, $object->getY());
        $this->assertEquals(100, $object->getWidth());
        $this->assertEquals(200, $object->getHeight());
        $this->assertEquals('/somefile', $object->getFilename());
        $this->assertEquals('666666', $object->getBackground()->getHex());

        /** @var Elements\Image $image */
        $image = $object->build();
        $this->assertInstanceOf(Elements\Image::class, $image);

        $this->assertEquals($object->getX(), $image->getX());
        $this->assertEquals($object->getY(), $image->getY());
        $this->assertEquals($object->getWidth(), $image->getWidth());
        $this->assertEquals($object->getHeight(), $image->getHeight());
        $this->assertEquals($object->getFilename(), $image->getFilename());
        $this->assertEquals($object->getBackground(), $image->getBackground());
    }
}
