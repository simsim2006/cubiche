<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Storage;

use Cubiche\Core\Collection\ArrayCollection\ArrayHashMap;
use Cubiche\Core\Collection\ArrayCollection\ArrayList;

/**
 * InMemoryMultidimensionalStorage class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryMultidimensionalStorage extends AbstractStorage implements MultidimensionalStorageInterface
{
    /**
     * @var ArrayHashMap
     */
    protected $store;

    /**
     * InMemoryMultidimensionalStorage constructor.
     */
    public function __construct()
    {
        $this->store = new ArrayHashMap();
    }

    /**
     * {@inheritdoc}
     */
    protected function has($key)
    {
        $this->validateKey($key);

        return $this->store->containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function push($key, $value)
    {
        if (!$this->has($key)) {
            $this->store->set($key, new ArrayList());
        }

        /** @var ArrayList $collection */
        $collection = $this->store->get($key);
        $collection->add($value);
    }

    /**
     * {@inheritdoc}
     */
    public function pop($key)
    {
        if (!$this->has($key)) {
            return;
        }

        /** @var ArrayList $collection */
        $collection = $this->store->get($key);

        // get the last element and remove from the collection
        $index = $collection->count() - 1;
        $sliced = $collection->slice($index, 1);
        $collection->removeAt($index);

        // if the collection is empty then remove the key
        if ($collection->count() == 0) {
            $this->store->removeAt($key);
        }

        return $sliced[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function all($key)
    {
        if (!$this->has($key)) {
            return array();
        }

        /** @var ArrayList $collection */
        $collection = $this->store->get($key);

        return $collection->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function count($key)
    {
        if (!$this->has($key)) {
            return 0;
        }

        /** @var ArrayList $collection */
        $collection = $this->store->get($key);

        return $collection->count();
    }

    /**
     * {@inheritdoc}
     */
    public function slice($key, $offset, $length = null)
    {
        if (!$this->has($key)) {
            return array();
        }

        /** @var ArrayList $collection */
        $collection = $this->store->get($key);

        return $collection->slice($offset, $length)->toArray();
    }
}
