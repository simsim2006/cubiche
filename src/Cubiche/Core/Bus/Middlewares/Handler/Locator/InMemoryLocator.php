<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Bus\Middlewares\Handler\Locator;

use Cubiche\Core\Bus\Exception\NotFoundException;
use Cubiche\Core\Collections\ArrayCollection\ArrayHashMap;

/**
 * InMemoryLocator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class InMemoryLocator implements LocatorInterface
{
    /**
     * @var ArrayHashMap
     */
    protected $handlers;

    /**
     * InMemoryLocator constructor.
     *
     * @param array $nameOfMessageToHandlerMap
     */
    public function __construct(array $nameOfMessageToHandlerMap = [])
    {
        $this->handlers = new ArrayHashMap();
        foreach ($nameOfMessageToHandlerMap as $nameOfMessage => $handler) {
            $this->addHandler($nameOfMessage, $handler);
        }
    }

    /**
     * @param string $nameOfMessage
     * @param object $handler
     */
    public function addHandler($nameOfMessage, $handler)
    {
        if (!is_string($nameOfMessage)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an string as a name of message. Instance of %s given',
                is_object($nameOfMessage) ? get_class($nameOfMessage) : gettype($nameOfMessage)
            ));
        }

        if (!is_object($handler)) {
            throw new \InvalidArgumentException(sprintf(
                'Expected an object as handler. Instance of %s given',
                gettype($handler)
            ));
        }

        $this->handlers->set($nameOfMessage, $handler);
    }

    /**
     * {@inheritdoc}
     */
    public function locate($nameOfMessage)
    {
        if (!$this->handlers->containsKey($nameOfMessage)) {
            throw NotFoundException::handlerFor($nameOfMessage);
        }

        return $this->handlers->get($nameOfMessage);
    }
}
