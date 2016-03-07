<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Collections\Tests\Units;

use Cubiche\Domain\Collections\ArrayCollection;
use Cubiche\Domain\Collections\ArrayCollectionInterface;
use Cubiche\Domain\Collections\Exception\InvalidKeyException;
use Cubiche\Domain\Collections\Tests\Units\Fixtures\ReverseComparator;
use Cubiche\Domain\Collections\Tests\Units\Fixtures\EquatableObject;
use Cubiche\Domain\Comparable\Comparator;

/**
 * ArrayCollectionTests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ArrayCollectionTests extends CollectionTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function randomCollection(array $items = array())
    {
        if (empty($items)) {
            foreach (range(0, rand(10, 20)) as $value) {
                $items[] = new EquatableObject(uniqid());
            }
        }

        return new ArrayCollection($items);
    }

    /**
     * {@inheritdoc}
     */
    protected function emptyCollection()
    {
        return new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueValue()
    {
        return new EquatableObject(1000);
    }

    /*
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($collection = $this->randomCollection())
            ->then
                ->collection($collection)
                    ->isInstanceOf(ArrayCollectionInterface::class)
        ;
    }

    /*
     * Test contains.
     */
    public function testContains()
    {
        $this
            ->given(
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then
                ->boolean($collection->contains($unique))
                    ->isFalse()
            ->and
            ->when($collection->add($unique))
            ->then
                ->boolean($collection->contains($unique))
                    ->isTrue()
        ;
    }

    /*
     * Test containsKey/set.
     */
    public function testContainsKey()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then
                ->boolean($collection->containsKey($key))
                    ->isFalse()
            ->and
            ->when($collection->set($key, $unique))
            ->then
                ->boolean($collection->containsKey($key))
                    ->isTrue()
            ->and
            ->exception(function () use ($collection, $unique) {
                $collection->set($unique, $unique);
            })->isInstanceOf(InvalidKeyException::class)
        ;
    }

    /*
     * Test removeAt.
     */
    public function testRemoveAt()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->when($collection->set($key, $unique))
            ->then
                ->boolean($collection->containsKey($key))
                    ->isTrue()
            ->and
            ->when($collection->removeAt($key))
            ->then
                ->boolean($collection->containsKey($key))
                    ->isFalse()
        ;
    }

    /*
     * Test get.
     */
    public function testGet()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then
                ->variable($collection->get($key))
                    ->isNull()
            ->and
            ->when($collection->set($key, $unique))
            ->then
                ->variable($collection->containsKey($key))
                    ->isEqualTo($unique)
        ;
    }

    /*
     * Test sort.
     */
    public function testSort()
    {
        $this
            ->given(
                $equatableComparator = new ReverseComparator(),
                $collection = $this->randomCollection()
            )
            ->when($collection->sort())
            ->then
                ->collection($collection)
                    ->isSorted()
            ->and
            ->when($collection->sort($equatableComparator))
            ->then
                ->collection($collection)
                    ->isSortedUsing($equatableComparator)
                ->collection($collection)
                    ->isNotSortedUsing(new Comparator())
        ;
    }

    /*
     * Test offsetExists.
     */
    public function testOffsetExists()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then
                ->boolean(isset($collection[$key]))
                    ->isFalse()
            ->and
            ->when($collection->set($key, $unique))
            ->then
                ->boolean(isset($collection[$key]))
                    ->isTrue()
        ;
    }

    /*
     * Test offsetGet.
     */
    public function testOffsetGet()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection()
            )
            ->then
                ->variable($collection[$key])
                    ->isNull()
            ->and
            ->when($collection->set($key, $unique))
            ->then
                ->variable($collection[$key])
                    ->isEqualTo($unique)
        ;
    }

    /*
     * Test offsetSet.
     */
    public function testOffsetSet()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->emptyCollection()
            )
            ->when($collection[$key] = $unique)
            ->then
                ->variable($collection[$key])
                    ->isEqualTo($unique)
            ->and
            ->when($collection[] = $key)
            ->then
                ->collection($collection)
                    ->contains($key)
        ;
    }

    /*
     * Test offsetUnset.
     */
    public function testOffsetUnset()
    {
        $this
            ->given(
                $key = 'foo',
                $unique = $this->uniqueValue(),
                $collection = $this->randomCollection(array($key => $unique))
            )
            ->when($collection[$key] = $unique)
            ->then
                ->variable($collection[$key])
                    ->isEqualTo($unique)
            ->and
            ->when(function () use ($collection, $key) {
                unset($collection[$key]);
            })
            ->then
                ->variable($collection[$key])
                    ->isNull()
        ;
    }
}