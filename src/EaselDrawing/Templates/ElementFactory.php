<?php

namespace EaselDrawing\Templates;

use EaselDrawing\ElementInterface;

class ElementFactory
{
    private $builders = [];

    public function register(string $type, string $builderClass)
    {
        $type = $this->castType($type);
        $implements = class_implements($builderClass);
        if (! in_array(ElementBuilderInterface::class, $implements)) {
            throw new \InvalidArgumentException(
                "Cannot register the class $builderClass since it does not implements " . ElementBuilderInterface::class
            );
        }
        $this->builders[$type] = $builderClass;
    }

    public function builderClass(string $type): string
    {
        $type = $this->castType($type);
        if (! isset($this->builders[$type])) {
            throw new \InvalidArgumentException("There are no builder class for type $type");
        }
        return $this->builders[$type];
    }

    public function has(string $type): bool
    {
        $type = $this->castType($type);
        return (isset($this->builders[$type]));
    }

    public function builder(string $type) : ElementBuilderInterface
    {
        $builderClass = $this->builderClass($type);
        /* @var ElementBuilderInterface $builder */
        $builder = call_user_func("$builderClass::create");

        return $builder;
    }

    public function element(string $type, $data, Template $template): ElementInterface
    {
        $builder = $this->builder($type);
        $builder->configure($data, $template);
        return $builder->build();
    }

    private function castType(string $type) : string
    {
        return strtolower($type);
    }
}
