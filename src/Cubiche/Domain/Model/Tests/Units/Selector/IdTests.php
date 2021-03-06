<?php

/**
 * This file is part of the Cubiche/Model component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Domain\Model\Tests\Units\Selector;

use Cubiche\Domain\Model\Selector\Id;
use Cubiche\Domain\Model\Tests\Units\TestCase;

/**
 * IdTests class.
 *
 * Generated by TestGenerator on 2016-05-03 at 16:01:26.
 */
class IdTests extends TestCase
{
    /**
     * Test create.
     */
    public function testCreate()
    {
        $this
            ->given($selector = new Id())
            ->then()
                ->string($selector->name())
                    ->isEqualTo('id')
        ;
    }
}
