<?php

namespace EaselDrawingTests\Templates;

use EaselDrawing\Templates\Builders\Image;
use EaselDrawing\Templates\ElementBuilderInterface;
use EaselDrawing\Templates\ElementFactory;
use EaselDrawingTests\Templates\Builders\BuilderTestCase;

class ElementFactoryTest extends BuilderTestCase
{
    public function testHasWithNonFound()
    {
        $factory = new ElementFactory();
        $this->assertFalse($factory->has('foo'));
    }

    public function testHasWithoutCase()
    {
        $factory = new ElementFactory();
        $factory->register('FOO', Image::class);
        $this->assertTrue($factory->has('foo'));
    }

    public function testRegisterThrowExceptionWithANonBuilderClass()
    {
        $factory = new ElementFactory();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(ElementBuilderInterface::class);

        $factory->register('FOO', \stdClass::class);
    }

    public function testBuilderClassThrowExceptionWhenNotRegistered()
    {
        $factory = new ElementFactory();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('type foo');
        $factory->builderClass('foo');
    }

    public function testRegisterAndElement()
    {
        $factory = new ElementFactory();
        $factory->register('image', Image::class);
        $this->assertTrue($factory->has('image'));
        $this->assertSame(Image::class, $factory->builderClass('image'));
        $this->assertInstanceOf(Image::class, $factory->builder('image'));
        $this->assertInstanceOf(
            \EaselDrawing\Elements\Image::class,
            $factory->element('image', [], $this->getBaseTemplate())
        );
    }
}
