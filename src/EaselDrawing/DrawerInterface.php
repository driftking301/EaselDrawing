<?php

namespace EaselDrawing;

interface DrawerInterface
{
    /**
     * Create the draw into a temporary file and return the name of the file
     *
     * @param Canvas $canvas
     * @return string
     */
    public function create(Canvas $canvas): string;
}
