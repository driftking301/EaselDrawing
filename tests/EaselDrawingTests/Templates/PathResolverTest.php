<?php

namespace EaselDrawingTests\Templates;

use EaselDrawing\Templates\PathResolver;
use PHPUnit\Framework\TestCase;

class PathResolverTest extends TestCase
{
    public function testConstructor()
    {
        $resolver = new PathResolver();
        $this->assertEquals('', $resolver->getRelativePath());
    }

    public function testSetRelativePath()
    {
        $resolver = new PathResolver();
        $resolver->setRelativePath('/my/project/');
        $this->assertEquals('/my/project', $resolver->getRelativePath());
    }

    public function testObtain()
    {
        $resolver = new PathResolver();
        $resolver->setRelativePath('/my/project/');

        $this->assertEquals('/absolute/path', $resolver->obtainPath('/absolute/path'));
        $this->assertEquals('/my/project/relative/path', $resolver->obtainPath('relative/path'));
    }
}
