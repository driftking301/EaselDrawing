<?php

namespace EaselDrawing;

class Elements implements \Countable, \IteratorAggregate
{
    /** @var ElementInterface[] elements */
    protected $elements = [];

    public function add(ElementInterface $element)
    {
        if (! $this->exists($element)) {
            $this->elements[] = $element;
        }
    }

    /**
     * @param ElementInterface[] $elements
     */
    public function addMulti(array $elements)
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * @param ElementInterface $element
     * @return false|int
     */
    public function search(ElementInterface $element)
    {
        return array_search($element, $this->elements, true);
    }

    public function remove(ElementInterface $element)
    {
        $id = $this->search($element);
        if (false !== $id) {
            unset($this->elements[$id]);
        }
    }

    public function exists(ElementInterface $element)
    {
        return (false !== $this->search($element));
    }

    public function getById(string $id): ElementInterface
    {
        foreach ($this->elements as $element) {
            if (0 === strcmp($id, $element->getId())) {
                return $element;
            }
        }
        throw new \InvalidArgumentException("The element $id does not exists");
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->elements);
    }
}
