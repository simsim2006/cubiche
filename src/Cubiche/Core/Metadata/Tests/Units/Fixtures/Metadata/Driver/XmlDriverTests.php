<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Units\Tests\Fixtures\Metadata\Driver;

use Cubiche\Core\Metadata\Driver\AbstractXmlDriver;
use Cubiche\Core\Metadata\Driver\DefaultFileLocator;
use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\Driver\XmlDriver;
use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\PropertyMetadata;
use Cubiche\Core\Metadata\Tests\Fixtures\WriteModel\Company;
use Cubiche\Core\Metadata\Tests\Units\Driver\XmlDriverTestCase;
use Doctrine\Common\Persistence\Mapping\Driver\DefaultFileLocator as DoctrineFileLocator;

/**
 * XmlDriverTests class.
 *
 * Generated by TestGenerator on 2016-11-01 at 12:47:41.
 */
class XmlDriverTests extends XmlDriverTestCase
{
    /**
     * @param array $paths
     *
     * @return AbstractXmlDriver
     */
    protected function createXmlDriver(array $paths = [])
    {
        $locator = new DoctrineFileLocator($paths);

        return new XmlDriver(
            new DefaultFileLocator($locator)
        );
    }

    /**
     * Test loadMetadataForClass method.
     */
    public function testLoadMetadataForClass()
    {
        $this
            ->given($driver = $this->createXmlDriver([__DIR__.'/../../../../Fixtures/mapping']))
            ->when($classMetadata = $driver->loadMetadataForClass(new \ReflectionClass(Company::class)))
            ->then()
                ->object($classMetadata)
                    ->isInstanceOf(ClassMetadata::class)
                ->string($classMetadata->targetClass)
                    ->isEqualTo('DDD\UI\ReadModel\Company')
                ->array($classMetadata->propertyMetadata)
                    ->hasSize(2)
                ->object($classMetadata->propertyMetadata['id'])
                    ->isInstanceOf(PropertyMetadata::class)
                ->object($classMetadata->propertyMetadata['name'])
                    ->isInstanceOf(PropertyMetadata::class)
                ->string($classMetadata->propertyMetadata['id']->projectionName)
                    ->isEqualTo('companyId')
                ->string($classMetadata->propertyMetadata['name']->projectionName)
                    ->isEqualTo('companyName')
        ;
    }
}
