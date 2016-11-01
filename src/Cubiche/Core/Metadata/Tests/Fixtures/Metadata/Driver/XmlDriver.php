<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures\Metadata\Driver;

use Cubiche\Core\Metadata\Driver\AbstractXmlDriver;
use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\PropertyMetadata;

/**
 * XmlDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class XmlDriver extends AbstractXmlDriver
{
    /**
     * {@inheritdoc}
     */
    protected function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $mapping = $this->getMapping($class->getName(), $file);
        if ($mapping === null) {
            throw new \Exception(sprintf(
                'Invalid mapping file %s for class %s.',
                $file,
                $class->getName()
            ));
        }

        $classMetadata = new ClassMetadata($class->getName());

        $projectionMapping = $this->extractElementAttributes($mapping);
        $classMetadata->setTargetClass($projectionMapping['targetClass']);
        foreach ($mapping->field as $item) {
            $fieldMapping = $this->extractElementAttributes($item);
            $propertyMetadata = new PropertyMetadata($class->getName(), $fieldMapping['name']);
            $propertyMetadata->setProjectionName($fieldMapping['projectionName']);

            $classMetadata->addPropertyMetadata($propertyMetadata);
        }

        return $classMetadata;
    }

    /**
     * Returns the extension of the file.
     *
     * @return string
     */
    protected function getExtension()
    {
        return '.xml';
    }

    /**
     * {@inheritdoc}
     */
    protected function loadMappingFile($file)
    {
        $result = array();
        $xmlElement = simplexml_load_file($file);
        foreach (array('projection') as $type) {
            if (isset($xmlElement->$type)) {
                foreach ($xmlElement->$type as $projectionElement) {
                    $projectionName = (string) $projectionElement['name'];
                    $result[$projectionName] = $projectionElement;
                }
            }
        }

        return $result;
    }
}
