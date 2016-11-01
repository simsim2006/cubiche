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

use Cubiche\Core\Metadata\Driver\AbstractAnnotationDriver;
use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\Annotations\Projection;
use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\Annotations\Property;
use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\PropertyMetadata;

/**
 * AnnotationDriver class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class AnnotationDriver extends AbstractAnnotationDriver
{
    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $classMetadata = new ClassMetadata($class->getName());

        $classAnnotations = $this->reader->getClassAnnotations($class);
        foreach ($classAnnotations as $annotation) {
            if ($annotation instanceof Projection) {
                $classMetadata->setTargetClass($annotation->targetClass);
            }
        }

        foreach ($class->getProperties() as $property) {
            $propertyAnnotations = $this->reader->getPropertyAnnotations($property);
            foreach ($propertyAnnotations as $propertyAnnotation) {
                if ($propertyAnnotation instanceof Property) {
                    $propertyMetadata = new PropertyMetadata($class->getName(), $property->getName());
                    $propertyMetadata->setProjectionName($propertyAnnotation->projectionName);

                    $classMetadata->addPropertyMetadata($propertyMetadata);
                }
            }
        }

        return $classMetadata;
    }
}
