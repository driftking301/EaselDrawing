<?php

namespace EaselDrawingTests;

use EaselDrawing\Color;
use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
    public function testCreateObject()
    {
        $color = new Color(255, 128, 64);
        $this->assertEquals(255, $color->getRed());
        $this->assertEquals(128, $color->getGreen());
        $this->assertEquals(64, $color->getBlue());
        $this->assertEquals('ff8040', $color->getHex());
    }

    public function providerNewFromString()
    {
        return [
            '3 hex' => ['ff9933', 'f93'],
            '6 hex' => ['ff9933', 'ff9933'],
            '+6 hex' => ['ff9933', 'ff993399'],
        ];
    }

    /**
     * @param string $expectedColor
     * @param string $input
     * @dataProvider providerNewFromString
     */
    public function testNewFromString($expectedColor, $input)
    {
        $color = Color::newFromString($input);
        $this->assertEquals($expectedColor, $color->getHex());
    }

    public function providerNewFromStringInvalid()
    {
        return [
            '2 digits' => ['f9'],
            '4 digits' => ['ff99'],
            '5 digits' => ['ff993'],
            '3 with invalid' => ['0h0'],
            '6 with invalid' => ['ffffxf'],
        ];
    }

    /**
     * @param string $input
     * @dataProvider providerNewFromStringInvalid
     */
    public function testNewFromStringInvalid($input)
    {
        $this->expectExceptionMessage('A color must contain 3 or 6 hexadecimal characters');
        Color::newFromString($input);
    }
}
