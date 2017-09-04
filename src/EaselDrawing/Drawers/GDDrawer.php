<?php

namespace EaselDrawing\Drawers;

use EaselDrawing\Align;
use EaselDrawing\Canvas;
use EaselDrawing\Color;
use EaselDrawing\DrawerInterface;
use EaselDrawing\Elements\Image;
use EaselDrawing\Elements\Label;
use EaselDrawing\Elements\Line;
use EaselDrawing\Elements\Rectangle;
use EaselDrawing\Orientation;
use EaselDrawing\TextBackground;

class GDDrawer implements DrawerInterface
{
    /** @var resource Image created with GD */
    private $im;

    public function create(Canvas $canvas): string
    {
        $this->im = imagecreatetruecolor($canvas->getWidth(), $canvas->getHeight());
        $backgroundColor = $this->colorAlloc($canvas->getBackground());
        imagefilledrectangle($this->im, 0, 0, $canvas->getWidth(), $canvas->getHeight(), $backgroundColor);

        foreach ($canvas->getElements() as $element) {
            if ($element instanceof Rectangle) {
                $this->drawRectangle($element);
                continue;
            }
            if ($element instanceof Line) {
                $this->drawLine($element);
                continue;
            }
            if ($element instanceof Label) {
                $this->drawLabel($element);
                continue;
            }
            if ($element instanceof Image) {
                $this->drawImage($element);
                continue;
            }
            throw new \LogicException("Don't know what to do with element type " . get_class($element));
        }
        if ($canvas->getOrientation()->getValue() === Orientation::PORTRAIT) {
            $this->drawPortrait($backgroundColor);
        }
        if ($canvas->isGrayScale()) {
            imagefilter($this->im, IMG_FILTER_GRAYSCALE);
        }
        $filename = tempnam(sys_get_temp_dir(), '');
        imagepng($this->im, $filename);
        return $filename;
    }

    public function drawRectangle(Rectangle $rectangle)
    {
        $color = $this->colorAlloc($rectangle->getColor());
        imagesetthickness($this->im, $rectangle->getThickness());
        $x1 = $rectangle->getX();
        $x2 = $rectangle->getWidth() + $rectangle->getX();
        $y1 = $rectangle->getY();
        $y2 = $rectangle->getHeight() + $rectangle->getY();
        imageline($this->im, $x1, $y1, $x2, $y1, $color); // top
        imageline($this->im, $x2, $y1, $x2, $y2, $color); // left
        imageline($this->im, $x1, $y2, $x2, $y2, $color); // top
        imageline($this->im, $x1, $y1, $x1, $y2, $color); // right
    }

    public function drawLine(Line $line)
    {
        $color = $this->colorAlloc($line->getColor());
        imagesetthickness($this->im, $line->getThickness());
        imageline($this->im, $line->getX(), $line->getY(), $line->getX2(), $line->getY2(), $color);
    }

    public function drawLabel(Label $label)
    {
        $font = $label->getFont();
        $fontFile = $font->getFile();
        $fontSize = $label->getSize();
        $width = $label->getWidth();
        $height = $label->getHeight();
        if (intval(round($fontSize, 0)) < 4) {
            throw new \RuntimeException('The font size is too small to fit');
        }

        // check if the content fits in the width and height
        $box = imagettfbbox($fontSize, 0, $fontFile, $label->getContent());
        if ($box[2] > $width or abs($box[5]) > $height) {
            $reducedLabel = clone $label;
            $reducedLabel->setSize($reducedLabel->getSize() - 1);
            $this->drawLabel($reducedLabel);
            return;
        }

        // the text fit into the area
        $x = $label->getX();
        $y = $label->getY() + ceil(($height - $box[5]) / 2);
        $align = $label->getAlign()->getValue();
        if ($align === Align::RIGHT) {
            $x = $label->getX() + $label->getWidth() - $box[2];
        }
        if ($align === Align::CENTER) { // center
            $x = $label->getX() + ceil(($label->getWidth() - $box[2]) / 2);
        }

        // draw text background
        $textBackground = $label->getTextBackground();
        if ($textBackground->getValue() === TextBackground::BOX) {
            imagefilledrectangle(
                $this->im,
                $label->getX(),
                $label->getY(),
                $label->getX() + $label->getWidth(),
                $label->getY() + $label->getHeight(),
                $this->colorAlloc($textBackground->getColor())
            );
        }
        if ($textBackground->getValue() === TextBackground::FIT) {
            imagefilledrectangle(
                $this->im,
                $x,
                $y,
                $x + $box[2],
                $y + $box[5],
                $this->colorAlloc($textBackground->getColor())
            );
        }

        // draw text
        $color = $this->colorAlloc($label->getColor());
        imagettftext($this->im, $fontSize, 0, $x, $y, $color, $fontFile, $label->getContent());
    }

    public function drawPortrait(int $allocatedBackground)
    {
        $this->im = imagerotate($this->im, 90, $allocatedBackground);
    }

    public function drawImage(Image $image)
    {
        $filename = $image->getFilename();
        $destWidth = $image->getWidth();
        $destHeight = $image->getHeight();
        $destX = $image->getX();
        $destY = $image->getY();

        $ix = $this->imageCreateFromFile($filename);

        $srcWidth = imagesx($ix);
        $srcHeight = imagesy($ix);
        $destRatio = $destWidth / $destHeight;
        $sourceRadio = $srcWidth / $srcHeight;
        if ($sourceRadio > $destRatio) {
            $finalWidth = $destWidth;
            $padX = 0;
            $finalHeight = round($destWidth * $srcHeight / $srcWidth, 0);
            $padY = floor(($destHeight - $finalHeight) / 2);
        } else {
            $finalHeight = $destHeight;
            $padY = 0;
            $finalWidth = round($destHeight * $srcWidth / $srcHeight, 0);
            $padX = floor(($destWidth - $finalWidth) / 2);
        }

        // draw background if exists
        if ($image->hasBackground()) {
            $color = $this->colorAlloc($image->getBackground());
            imagefilledrectangle($this->im, $destX, $destY, $destX + $destWidth, $destY + $destHeight, $color);
        }

        // draw image
        $copy = imagecopyresized(
            $this->im,
            $ix,
            $destX + $padX,
            $destY + $padY,
            0,
            0,
            $finalWidth,
            $finalHeight,
            $srcWidth,
            $srcHeight
        );
        if (! $copy) {
            throw new \RuntimeException("Cannot import the image $filename");
        }
    }

    /**
     * Open an image as a GD resource
     *
     * @param string $filename
     * @return resource
     */
    protected function imageCreateFromFile(string $filename)
    {
        if (! file_exists($filename) || ! is_readable($filename)) {
            throw new \InvalidArgumentException("The file $filename does not exists or is not readable");
        }
        $filetype = (new \finfo())->file($filename, FILEINFO_MIME_TYPE);
        if ('image/jpeg' == $filetype) {
            return imagecreatefromjpeg($filename);
        }
        if ('image/png' == $filetype) {
            return imagecreatefrompng($filename);
        }
        if ('image/gif' == $filetype) {
            return imagecreatefromgif($filename);
        }
        if ('image/bmp' == $filetype) {
            return imagecreatefromwbmp($filename);
        }
        throw new \InvalidArgumentException("The file $filename is not a valid file type");
    }

    protected function colorAlloc(Color $color): int
    {
        return imagecolorallocate($this->im, ...$color->getRGB());
    }
}
