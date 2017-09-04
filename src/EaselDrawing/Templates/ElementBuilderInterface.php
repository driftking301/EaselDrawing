<?php

namespace EaselDrawing\Templates;

use EaselDrawing\ElementInterface;

interface ElementBuilderInterface
{
    public static function create(): ElementBuilderInterface;

    public function configure(array $data, Template $template);

    public function build(): ElementInterface;
}
