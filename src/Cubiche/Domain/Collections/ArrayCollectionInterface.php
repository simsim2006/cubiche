<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections;

use Cubiche\Domain\Comparable\ComparatorInterface;

/**
 * ArrayCollection Interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
interface ArrayCollectionInterface extends CollectionInterface, \ArrayAccess
{
    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param string|int $key
     * @param mixed      $value
     */
    public function set($key, $value);

    /**
     * @param ComparatorInterface $comparator
     */
    public function sort(ComparatorInterface $comparator = null);
}
