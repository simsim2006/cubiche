<?php

/**
 * This file is part of the Cubiche/Web component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Web\Tests\Units;

use Cubiche\Domain\System\Tests\Units\StringLiteralTests;
use Cubiche\Domain\Web\Path;

/**
 * PathTests class.
 *
 * Generated by TestGenerator on 2017-03-15 at 11:36:08.
 */
class PathTests extends StringLiteralTests
{
    /**
     * {@inheritdoc}
     */
    protected function randomNativeValue()
    {
        return $this->faker->file('/tmp', __DIR__);
    }

    /**
     * {@inheritdoc}
     */
    protected function invalidNativeValue()
    {
        return '//valid?';
    }

    /**
     * {@inheritdoc}
     */
    protected function uniqueNativeValue()
    {
        return $this->faker->unique()->file('/tmp', __DIR__);
    }

    /**
     * {@inheritdoc}
     */
    protected function fromNative($value)
    {
        return Path::fromNative($value);
    }
}
