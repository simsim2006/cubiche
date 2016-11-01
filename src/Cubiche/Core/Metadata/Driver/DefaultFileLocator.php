<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Driver;

use Doctrine\Common\Persistence\Mapping\Driver\FileLocator;
use Metadata\Driver\FileLocatorInterface;

/**
 * DefaultFileLocator class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class DefaultFileLocator implements FileLocatorInterface
{
    /**
     * @var FileLocator
     */
    protected $locator;

    /**
     * DefaultFileLocator constructor.
     *
     * @param FileLocator $locator
     */
    public function __construct(FileLocator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @param \ReflectionClass $class
     * @param string           $extension
     *
     * @return string|null
     */
    public function findFileForClass(\ReflectionClass $class, $extension)
    {
        if (method_exists($this->locator, 'setFileExtension')) {
            $this->locator->setFileExtension($extension);
        }

        return $this->locator->findMappingFile($class->getName());
    }
}
