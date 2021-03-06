<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Bus\Tests\Units\Middlewares\Handler\Locator;

use Cubiche\Core\Bus\Middlewares\Handler\Locator\InMemoryLocator;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessage;
use Cubiche\Core\Bus\Tests\Fixtures\Message\LoginUserMessageListener;

/**
 * InMemoryLocator class.
 *
 * Generated by TestGenerator on 2016-04-07 at 15:40:41.
 */
class InMemoryLocatorTests extends LocatorTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createLocator()
    {
        return new InMemoryLocator();
    }

    /**
     * Test create.
     */
    public function testCreate()
    {
        parent::testCreate();

        $this
            ->given($handler = new LoginUserMessageListener())
            ->then()
//                ->exception(function () use ($handler) {
//                    new InMemoryLocator([255 => $handler]);
//                })
//                ->isInstanceOf(\InvalidArgumentException::class)
                ->exception(function () use ($handler) {
                    new InMemoryLocator([LoginUserMessage::class => 255]);
                })
                ->isInstanceOf(\InvalidArgumentException::class)

        ;
    }
}
