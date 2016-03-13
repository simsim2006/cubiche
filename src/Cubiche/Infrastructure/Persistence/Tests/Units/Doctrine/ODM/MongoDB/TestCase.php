<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Tests\Units\Doctrine\ODM\MongoDB;

use Cubiche\Domain\Tests\Units\TestCase as BaseTestCase;

/**
 * Abstract Test Case Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    use DocumentManagerTestCaseTrait;
}
