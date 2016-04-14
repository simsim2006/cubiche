<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Collections;

use Cubiche\Core\Comparable\Comparator;
use Cubiche\Core\Comparable\ComparatorInterface;
use Cubiche\Core\Specification\Criteria;
use Cubiche\Core\Specification\SpecificationInterface;
use Cubiche\Core\Collections\DataSource\ArrayDataSource;
use Cubiche\Core\Collections\Exception\InvalidKeyException;

/**
 * Array Collection Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayCollection implements ArrayCollectionInterface
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @param array $items
     */
    public function __construct(array $items = array())
    {
        $this->items = $items;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\CollectionInterface::add()
     */
    public function add($item)
    {
        $this->items[] = $item;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\CollectionInterface::addAll()
     */
    public function addAll($items)
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\CollectionInterface::remove()
     */
    public function remove($item)
    {
        $criteria = Criteria::eq($item);
        foreach ($this->items as $key => $value) {
            if ($criteria->evaluate($value)) {
                unset($this->items[$key]);
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\ArrayCollectionInterface::removeAt()
     */
    public function removeAt($key)
    {
        if ($this->containsKey($key)) {
            unset($this->items[$key]);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\CollectionInterface::clear()
     */
    public function clear()
    {
        $this->items = array();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\ArrayCollectionInterface::contains()
     */
    public function contains($item)
    {
        return \in_array($item, $this->items, true);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\ArrayCollectionInterface::exists()
     */
    public function containsKey($key)
    {
        $this->validateKey($key);

        return isset($this->items[$key]) || \array_key_exists($key, $this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\ArrayCollectionInterface::get()
     */
    public function get($key)
    {
        $this->validateKey($key);

        return isset($this->items[$key]) ? $this->items[$key] : null;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\ArrayCollectionInterface::set()
     */
    public function set($key, $value)
    {
        $this->validateKey($key);

        $this->items[$key] = $value;
    }

    /**
     * {@inheritdoc}
     *
     * @see Countable::count()
     */
    public function count()
    {
        return \count($this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\CollectionInterface::slice()
     */
    public function slice($offset, $length = null)
    {
        return new static(\array_slice($this->items, $offset, $length, true));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\CollectionInterface::find()
     */
    public function find(SpecificationInterface $criteria)
    {
        return new DataSourceCollection(new ArrayDataSource($this->items, $criteria));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\CollectionInterface::findOne()
     */
    public function findOne(SpecificationInterface $criteria)
    {
        return (new ArrayDataSource($this->items, $criteria))->findOne();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\CollectionInterface::toArray()
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\ArrayCollectionInterface::sort()
     */
    public function sort(ComparatorInterface $criteria = null)
    {
        if ($criteria === null) {
            $criteria = new Comparator();
        }

        usort($this->items, function ($a, $b) use ($criteria) {
            return $criteria->compare($a, $b);
        });
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\CollectionInterface::sorted()
     */
    public function sorted(ComparatorInterface $criteria)
    {
        return new DataSourceCollection(new ArrayDataSource($this->items, null, $criteria));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\ArrayCollectionInterface::keys()
     */
    public function keys()
    {
        return new self(\array_keys($this->items));
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Collections\ArrayCollectionInterface::values()
     */
    public function values()
    {
        return new self(\array_values($this->items));
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }

        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * Validates that a key is valid.
     *
     * @param int|string $key
     *
     * @return bool
     *
     * @throws InvalidKeyException If the key is invalid.
     */
    protected function validateKey($key)
    {
        if (!is_string($key) && !is_int($key)) {
            throw InvalidKeyException::forKey($key);
        }

        return true;
    }
}