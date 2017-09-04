<?php

namespace EaselDrawing\Templates;

use EaselDrawing\Color;
use EaselDrawing\Font;
use EaselDrawing\Orientation;

class Utilities
{

    /**
     * @param array|string $value
     * @param Color $default
     * @return Color
     */
    public static function interpretColor($value, Color $default): Color
    {
        // color as RGB
        if (is_array($value)) {
            return Color::newFromArray($value);
        }
        // color as CSS hex definition
        if (is_string($value)) {
            return Color::newFromString($value);
        }
        return $default;
    }

    /**
     * @param string $name
     * @param string|array $data
     * @param PathResolver $pathResolver
     * @return Font
     */
    public static function interpretFont(string $name, $data, PathResolver $pathResolver): Font
    {
        if (is_string($data)) {
            /* Font defined as
             * Arial: /usr/share/fonts/truetype/Arial.ttf
             */
            $isBold = false;
            $isItalic = false;
            $location = $data;
        } elseif (is_array($data)) {
            /* Font defined as
             * Arial:
             *     - location: /usr/share/fonts/truetype/ArialBoldItalic.ttf
             *     - bold: false
             *     - italic: false
             */
            $isBold = (isset($data['bold'])) ? (bool) $data['bold'] : false;
            $isItalic = (isset($data['italic'])) ? (bool) $data['italic'] : false;
            $location = (isset($data['location'])) ? (string) $data['location'] : '';
        } else {
            throw new \InvalidArgumentException("Cannot interpret fonts as one of them is not a string or an array");
        }
        $location = $pathResolver->obtainPath($location);

        return new Font($name, $isBold, $isItalic, $location);
    }

    public static function interpretOrientation(string $orientation)
    {
        return Orientation::newFromString($orientation);
    }
}
