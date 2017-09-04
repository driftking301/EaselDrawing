<?php

namespace EaselDrawingTests;

use EaselDrawing\Align;
use EaselDrawing\Canvas;
use EaselDrawing\Color;
use EaselDrawing\DrawerInterface;
use EaselDrawing\Drawers\GDDrawer;
use EaselDrawing\Elements;
use EaselDrawing\Font;
use EaselDrawing\Orientation;
use EaselDrawing\TextBackground;
use Lupka\PHPUnitCompareImages\CompareImagesTrait;
use PHPUnit\Framework\TestCase;

class DevelopmentCaseTest extends TestCase
{
    use CompareImagesTrait;

    public function testCanvasCreateObject()
    {
        if (! class_exists('\Imagick')) {
            $this->markTestSkipped('No Imagick extension found');
        }
        $orientation = Orientation::newFromString(Orientation::PORTRAIT);
        $canvas = new Canvas(600, 400, null, null, true, $orientation);
        $tbWhite = new TextBackground(TextBackground::BOX, new Color(255, 255, 255));
        $red = new Color(255, 0, 0);
        $black = new Color(0, 0, 0);
        $f1 = new Font('DejaVuSans', false, false, test_asset('fonts/DejaVuSans/DejaVuSans.ttf'));
        $f2 = new Font('DejaVuSans', false, false, test_asset('fonts/DejaVuSans/DejaVuSans_Bold.ttf'));
        $elements = $canvas->getElements();
        $aCenter = new Align(Align::CENTER);
        $aRight = new Align(Align::RIGHT);
        $elements->addMulti([
            new Elements\Rectangle(10, 10, 180, 180, $black, 3),
            new Elements\Image(10, 10, 180, 180, test_asset('photos/avatar-sample-300x300.png')),
            new Elements\Image(10, 300, 180, 90, test_asset('photos/logo.png')),
            new Elements\Label(200, 10, 390, 50, 'VISITOR', $f1, 40, $red, $aRight, $tbWhite),
            new Elements\Label(200, 120, 390, 50, "Carlos C Soto", $f2, 40, $black, $aCenter, $tbWhite),
            new Elements\Label(200, 180, 390, 50, 'Open Source LTD', $f1, 40, $black, $aCenter, $tbWhite),
            new Elements\Label(200, 270, 390, 50, 'Foo Bar Inkeeper', $f1, 40, $black, $aCenter, $tbWhite),
            new Elements\Label(400, 320, 190, 50, '13/Jan 13:25', $f1, 80, $red, $aRight, $tbWhite),
            new Elements\Line(5, 5, 590, 0, $black, 3),
        ]);

        /** @var DrawerInterface $drawer */
        $drawer = new GDDrawer();
        $filename = $drawer->create($canvas);
        $this->assertFileExists($filename);
        $expectedFile = test_asset('photos/draw.png');
        $this->assertImageSimilarity($filename, $expectedFile, 0, "File $filename does not match with $expectedFile");
        unlink($filename);
    }
}
