<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Encoder;

use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\ClassMetadataFactoryInterface;
use Cubiche\Core\Metadata\PropertyMetadata;
use Cubiche\Core\Serializer\Exception\SerializationException;
use Cubiche\Core\Serializer\SerializerAwareInterface;
use Cubiche\Core\Serializer\SerializerAwareTrait;

/**
 * ObjectEncoder class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
class ObjectEncoder implements SerializerAwareInterface
{
    use SerializerAwareTrait;

    /**
     * @var ClassMetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * ObjectEncoder constructor.
     *
     * @param ClassMetadataFactoryInterface $metadataFactory
     */
    public function __construct(ClassMetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * @param string $className
     *
     * @return mixed
     */
    public function supports($className)
    {
        try {
            new \ReflectionClass($className);

            return true;
        } catch (\ReflectionException $exception) {
            return $className == 'object';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function encode($object)
    {
        $result = array();

        $classMetadata = $this->getClassMetadata(get_class($object));
        if ($classMetadata === null) {
            throw SerializationException::invalidMapping(get_class($object));
        }

        /** @var PropertyMetadata $propertyMetadata */
        foreach ($classMetadata->propertiesMetadata() as $propertyMetadata) {
            $result[$propertyMetadata->propertyName()] = $this->serializer->serialize(
                $propertyMetadata->getValue($object)
            );
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($data, $className)
    {
        $classMetadata = $this->getClassMetadata($className);
        if ($classMetadata === null) {
            throw SerializationException::invalidMapping($className);
        }

        $object = $classMetadata->reflection()->newInstanceWithoutConstructor();
        /** @var PropertyMetadata $propertyMetadata */
        foreach ($classMetadata->propertiesMetadata() as $propertyMetadata) {
            if (!array_key_exists($propertyMetadata->propertyName(), $data)) {
                throw SerializationException::propertyNotFound($propertyMetadata->propertyName(), $className);
            }

            $propertyValue = $data[$propertyMetadata->propertyName()];
            $propertyType = $propertyMetadata->getMetadata('type');
            if ($propertyMetadata->getMetadata('of') !== null) {
                $propertyType = $propertyMetadata->getMetadata('of');
            }

            if ($propertyType === null) {
                $propertyType = is_object($propertyValue) ? get_class($propertyValue) : gettype($propertyValue);
            }

            $propertyValue = $this->serializer->deserialize($propertyValue, $propertyType);

            $propertyMetadata->setValue($object, $propertyValue);
        }

        return $object;

//        $reflection = new \ReflectionClass($className);
//        $object = $reflection->newInstanceWithoutConstructor();

//        foreach ($reflection->getProperties() as $property) {
//            $propertyName = $property->getName();

//            if (!array_key_exists($propertyName, $data)) {
//                throw SerializationException::propertyNotFound($propertyName, $className);
//            }

//            $propertyValue = $this->encoder->decode($data[$propertyName]);

//            $property->setAccessible(true);
//            $property->setValue($object, $propertyValue);
//            $property->setAccessible(false);
//        }

//        return $object;
    }

    /**
     * Returns the metadata for a class.
     *
     * @param string $className
     *
     * @return ClassMetadata
     */
    protected function getClassMetadata($className)
    {
        return $this->metadataFactory->getMetadataFor(ltrim($className, '\\'));
    }
}
