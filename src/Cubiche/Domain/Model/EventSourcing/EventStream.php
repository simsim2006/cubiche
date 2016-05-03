<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Model\EventSourcing;

use Cubiche\Domain\Model\IdInterface;
use Cubiche\Domain\System\StringLiteral;

/**
 * EventStream class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class EventStream
{
    /**
     * @var StringLiteral
     */
    protected $className;

    /**
     * @var IdInterface
     */
    protected $aggregateId;

    /**
     * @var EntityDomainEventInterface[]
     */
    protected $events = [];

    /**
     * EntityDomainEvent constructor.
     *
     * @param StringLiteral                $className
     * @param IdInterface                  $aggregateId
     * @param EntityDomainEventInterface[] $events
     */
    public function __construct(
        StringLiteral $className,
        IdInterface $aggregateId,
        array $events
    ) {
        $this->className = $className;
        $this->aggregateId = $aggregateId;

        foreach ($events as $event) {
            $this->addEvent($event);
        }
    }

    /**
     * @return StringLiteral
     */
    public function className()
    {
        return $this->className;
    }

    /**
     * @return IdInterface
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @return EntityDomainEventInterface[]
     */
    public function events()
    {
        return $this->events;
    }

    /**
     * @param EntityDomainEventInterface $event
     */
    protected function addEvent(EntityDomainEventInterface $event)
    {
        $this->events[] = $event;
    }
}