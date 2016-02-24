<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Tests\Atoum\Asserters;

use mageekguy\atoum\asserters\mock as BaseMock;

/**
 * Mock class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class MockBuilder extends BaseMock
{
    /**
     * {@inheritdoc}
     */
    public function __call($method, $arguments)
    {
        switch ($method) {
            case 'with':
                return $this->callIsSet();

            case 'arguments':
                if (sizeof($arguments) < 2 || !\is_callable($arguments[1])) {
                    throw new \Exception(
                        'arguments asserted require a int for the argument 0 and a callable function as 2 argument'
                    );
                }

                $currentCall = $this->getCall();

                $asserter = $this->getCalls($currentCall)->getEqualTo($currentCall)->toArray()[2]->getArguments();
                $argument = $asserter[$arguments[0]];

                $arguments[1]($argument);

                return $this->callIsSet();
            default:
                return parent::__call($method, $arguments);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __get($property)
    {
        switch (strtolower($property)) {
            case 'with':
                return $this->{$property}();

            default:
                return parent::__get($property);
        }
    }

    /**
     * @return $this
     */
    public function with()
    {
        return $this->callIsSet();
    }
}