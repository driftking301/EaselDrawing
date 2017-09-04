<?php

namespace EaselDrawingTests;

use EaselDrawing\ElementInterface;

class DummyElement implements ElementInterface
{
    private $id = '';

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
