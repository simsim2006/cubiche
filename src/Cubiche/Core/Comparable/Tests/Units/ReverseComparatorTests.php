<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Comparable\Tests\Units;

use Cubiche\Core\Comparable\Comparator;

/**
 * Reverse Comparator Tests class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class ReverseComparatorTests extends ComparatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function defaultConstructorArguments()
    {
        return array(new Comparator());
    }

    /**
     * {@inheritdoc}
     */
    protected function compareDataProvider()
    {
        foreach (parent::compareDataProvider() as $key => $data) {
            $data[2] *= -1;
            yield $key => $data;
        }
    }
}
