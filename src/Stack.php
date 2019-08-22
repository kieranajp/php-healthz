<?php
namespace Gentux\Healthz;

/**
 * Helper for managing collections of stuffs
 *
 * @package Gentux\Healthz
 */
trait Stack
{

    protected $items = [];

    /**
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    public function push($item)
    {
        $this->items[] = $item;

        return $this;
    }

    public function merge(array $items)
    {
        $this->items = array_merge($this->items, $items);

        return $this;
    }

    public function replace(array $items)
    {
        $this->items = $items;

        return $this;
    }
}
