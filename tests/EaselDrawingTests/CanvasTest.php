<?php

namespace EaselDrawingTests;

use EaselDrawing\Canvas;
use EaselDrawing\Elements;
use EaselDrawing\Orientation;
use PHPUnit\Framework\TestCase;

class CanvasTestTest extends TestCase
{
    public function testConstructWithMinimalOptions()
    {
        $canvas = new Canvas(10, 20);
        $this->assertEquals(10, $canvas->getWidth());
        $this->assertEquals(20, $canvas->getHeight());
        $this->assertEquals('ffffff', $canvas->getBackground()->getHex());
        $this->assertInstanceOf(Elements::class, $canvas->getElements());
        $this->assertCount(0, $canvas->getElements());
        $this->assertEquals(Orientation::LANDSCAPE, $canvas->getOrientation()->getValue());
    }
}
