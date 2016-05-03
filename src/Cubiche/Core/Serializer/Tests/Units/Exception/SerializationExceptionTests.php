<?php

/**
 * This file is part of the Cubiche/Serializer component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Tests\Units\Exception;

use Cubiche\Core\Serializer\Exception\SerializationException;
use Cubiche\Core\Serializer\Tests\Units\TestCase;

/**
 * SerializationException class.
 *
 * Generated by TestGenerator on 2016-04-29 at 17:37:57.
 */
class SerializationExceptionTests extends TestCase
{
    /**
     * Test class.
     */
    public function testClass()
    {
        $this
            ->testedClass
                ->extends(\RuntimeException::class)
        ;
    }

    /*
     * Test forObject method.
     */
    public function testForObject()
    {
        $this
            ->given($cause = new \Exception('some cause'))
            ->when($exception = SerializationException::forObject('foo', $cause))
            ->then()
                ->object($exception)
                    ->isInstanceOf(SerializationException::class)
                ->integer($exception->getCode())
                    ->isEqualTo(0)
                ->object($exception->getPrevious())
                    ->isIdenticalTo($cause)
        ;

        $this
            ->given($exception = SerializationException::forObject('bar'))
            ->then()
                ->variable($exception->getPrevious())
                    ->isNull()
        ;
    }
}