<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Infrastructure\Persistence\Tests\Units\Doctrine\ODM\MongoDB\Types;

use Cubiche\Infrastructure\Identity\Persistence\Doctrine\ODM\MongoDB\Types\UUIDType;
use Cubiche\Domain\Persistence\Tests\Fixtures\UserId;

/**
 * User Id Type Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class UserIdType extends UUIDType
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Infrastructure\Identity\Persistence\Doctrine\ODM\MongoDB\Types\UUIDType::nativeValueObjectClass()
     */
    protected function nativeValueObjectClass()
    {
        return UserId::class;
    }
}