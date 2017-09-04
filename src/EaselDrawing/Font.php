<?php

namespace EaselDrawing;

class Font
{
    /** @var string */
    private $family;
    /** @var bool */
    private $isBold;
    /** @var bool */
    private $isItalic;
    /** @var string */
    private $file;

    public function __construct(string $family, bool $isBold, bool $isItalic, string $file)
    {
        $this->family = $family;
        $this->isBold = $isBold;
        $this->isItalic = $isItalic;
        $this->file = $file;
    }

    public function getFamily() : string
    {
        return $this->family;
    }

    public function isBold() : bool
    {
        return $this->isBold;
    }

    public function isItalic() : bool
    {
        return $this->isItalic;
    }

    public function getFile() : string
    {
        return $this->file;
    }
}
