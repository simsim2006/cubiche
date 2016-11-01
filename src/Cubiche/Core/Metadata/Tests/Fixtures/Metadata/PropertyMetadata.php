<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures\Metadata;

use Cubiche\Core\Metadata\PropertyMetadata as BasePropertyMetadata;

/**
 * PropertyMetadata class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class PropertyMetadata extends BasePropertyMetadata
{
    /**
     * @var string
     */
    public $projectionName;

    /**
     * @param string $projectionName
     */
    public function setProjectionName($projectionName)
    {
        $this->projectionName = $projectionName;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->class,
            $this->name,
            $this->projectionName,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        list(
            $this->class,
            $this->name,
            $this->projectionName
        ) = unserialize($str);

        $this->reflection = new \ReflectionProperty($this->class, $this->name);
        $this->reflection->setAccessible(true);
    }
}
