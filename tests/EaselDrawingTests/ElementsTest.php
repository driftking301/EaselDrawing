<?php

namespace EaselDrawingTests;

use EaselDrawing\Elements;
use PHPUnit\Framework\TestCase;

class ElementsTest extends TestCase
{
    public function testNewInstance()
    {
        $elements = new Elements();
        $this->assertCount(0, $elements);
    }

    public function testAddOneElement()
    {
        $elements = new Elements();
        $element = new DummyElement();
        $elements->add($element);

        $this->assertCount(1, $elements);
        $this->assertTrue($elements->exists($element));
    }

    public function testAddTwoTimesTheSameElementAddOnlyOne()
    {
        $elements = new Elements();
        $element = new DummyElement();
        $elements->add($element);
        $elements->add($element);

        $this->assertCount(1, $elements);
        $this->assertTrue($elements->exists($element));
    }

    public function testAddMulti()
    {
        $element = new DummyElement();
        $array = [
            new DummyElement(),
            new DummyElement(),
            new DummyElement(),
            $element,
            $element,
        ];

        $elements = new Elements();
        $elements->addMulti($array);
        $this->assertCount(4, $elements);
    }

    public function testGetById()
    {
        $elements = new Elements();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not exists');
        $elements->getById('foo');
    }

    public function testGetReceiveSameElement()
    {
        $element = new DummyElement();
        $element->setId('foo');
        $elements = new Elements();
        $elements->add(new DummyElement());
        $elements->add($element);

        $this->assertSame($element, $elements->getById('foo'));
    }

    public function testRemove()
    {
        $one = new DummyElement();
        $two = new DummyElement();
        $other = new DummyElement();

        $elements = new Elements();
        $elements->addMulti([$one, $two]);

        $this->assertCount(2, $elements);
        $elements->remove($other);
        $this->assertCount(2, $elements);

        $elements->remove($one);
        $this->assertCount(1, $elements);

        $elements->remove($one);
        $this->assertCount(1, $elements);

        $elements->remove($two);
        $this->assertCount(0, $elements);
    }

    public function testTraverdable()
    {
        $one = new DummyElement();
        $two = new DummyElement();

        $elements = new Elements();
        $elements->addMulti([$one, $two]);

        $collection = [];
        foreach ($elements as $element) {
            $collection[] = $element;
        }

        $this->assertSame([$one, $two], $collection);
    }
}
