<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Specification\Tests\Units\Constraint;

/**
 * Not Same Tests Class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class NotSameTests extends BinaryConstraintOperatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function shouldVisitMethod()
    {
        return 'visitNotSame';
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateSuccessDataProvider()
    {
        return array(
            array(4.999),
            array(5.0),
            array(6),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function evaluateFailureDataProvider()
    {
        return array(
            array(5),
        );
    }
}
