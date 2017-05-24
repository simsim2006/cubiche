<?php

/**
 * This file is part of the Cubiche/Metadata component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Units;

use Cubiche\Core\Metadata\Cache\FileCache;
use Cubiche\Core\Metadata\ClassMetadata;
use Cubiche\Core\Metadata\ClassMetadataFactory;
use Cubiche\Core\Metadata\Tests\Fixtures\Address;
use Cubiche\Core\Metadata\Tests\Fixtures\Driver\AnnotationDriver;
use Cubiche\Core\Metadata\Tests\Fixtures\User;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;
use mageekguy\atoum\adapter as Adapter;
use mageekguy\atoum\annotations\extractor as Extractor;
use mageekguy\atoum\asserter\generator as Generator;
use mageekguy\atoum\test\assertion\manager as Manager;
use mageekguy\atoum\tools\variable\analyzer as Analyzer;

/**
 * ClassMetadataFactoryTests class.
 *
 * Generated by TestGenerator on 2017-05-16 at 13:17:21.
 *
 * @engine isolate
 */
class ClassMetadataFactoryTests extends TestCase
{
    /**
     * @var string
     */
    protected $cacheDirectory;

    /**
     * @param Adapter   $adapter
     * @param Extractor $annotationExtractor
     * @param Generator $asserterGenerator
     * @param Manager   $assertionManager
     * @param \Closure  $reflectionClassFactory
     * @param \Closure  $phpExtensionFactory
     * @param Analyzer  $analyzer
     */
    public function __construct(
        Adapter $adapter = null,
        Extractor $annotationExtractor = null,
        Generator $asserterGenerator = null,
        Manager $assertionManager = null,
        \Closure $reflectionClassFactory = null,
        \Closure $phpExtensionFactory = null,
        Analyzer $analyzer = null
    ) {
        parent::__construct(
            $adapter,
            $annotationExtractor,
            $asserterGenerator,
            $assertionManager,
            $reflectionClassFactory,
            $phpExtensionFactory,
            $analyzer
        );

        $this->cacheDirectory = __DIR__.'/Factory/Cache';
    }

    /**
     * Create the cache directory.
     */
    public function setUp()
    {
        if (!is_dir($this->cacheDirectory)) {
            mkdir($this->cacheDirectory);
        }
    }

    /**
     * Remove the cache directory.
     */
    public function tearDown()
    {
        $this->rmdir($this->cacheDirectory);
    }

    /**
     * @return AnnotationDriver
     */
    protected function createDriver()
    {
        $reader = new CachedReader(
            new AnnotationReader(),
            new FilesystemCache($this->cacheDirectory),
            $debug = true
        );

        AnnotationRegistry::registerFile(__DIR__.'/../Fixtures/Annotations/AggregateRoot.php');
        AnnotationRegistry::registerFile(__DIR__.'/../Fixtures/Annotations/Entity.php');
        AnnotationRegistry::registerFile(__DIR__.'/../Fixtures/Annotations/Field.php');
        AnnotationRegistry::registerFile(__DIR__.'/../Fixtures/Annotations/Id.php');

        $driver = new AnnotationDriver($reader, [__DIR__.'/../Fixtures']);
        $driver->addExcludePaths([
            __DIR__.'/../Fixtures/Annotations',
            __DIR__.'/../Fixtures/Driver',
            __DIR__.'/../Fixtures/mapping',
            __DIR__.'/../Fixtures/mapping-two',
        ]);
        $driver->setFileExtension('.php');

        return $driver;
    }

    /**
     * @return FileCache
     */
    protected function createCache()
    {
        return new FileCache($this->cacheDirectory);
    }

    /**
     * @return ClassMetadataFactory
     */
    protected function createFactory()
    {
        return new ClassMetadataFactory($this->createDriver(), $this->createCache());
    }

    /**
     * @return ClassMetadataFactory
     */
    protected function createFactoryWithoutCache()
    {
        return new ClassMetadataFactory($this->createDriver());
    }

    /**
     * Test GetAllMetadata method.
     */
    public function testGetAllMetadata()
    {
        $this
            ->given($factory = $this->createFactoryWithoutCache())
            ->when($metadatas = $factory->getAllMetadata())
            ->then()
                ->array($metadatas)
                    ->hasSize(2)
        ;

        $this
            ->given($factory = $this->createFactory())
            ->when($metadatas = $factory->getAllMetadata())
            ->then()
                ->array($metadatas)
                    ->hasSize(2)
        ;
    }

    /**
     * Test GetMetadataFor method.
     */
    public function testGetMetadataFor()
    {
        $this
            ->given($factory = $this->createFactory())
            ->when($metadatas = $factory->getAllMetadata())
            ->then()
                ->array($metadatas)
                    ->hasSize(2)
                ->object($classMetadata = $factory->getMetadataFor(User::class))
                    ->isInstanceOf(ClassMetadata::class)
                ->array($classMetadata->propertiesMetadata())
                    ->hasSize(7)
                    ->hasKey('id')
                    ->hasKey('name')
                    ->hasKey('username')
                    ->hasKey('age')
                    ->hasKey('email')
                    ->hasKey('addresses')
                    ->hasKey('friends')
                ->object($propertyMetadata = $classMetadata->propertyMetadata('id'))
                    ->isNotNull()
                ->boolean($propertyMetadata->getMetadata('identifier'))
                    ->isTrue()
                ->string($propertyMetadata->getMetadata('name'))
                    ->isEqualTo('_id')
                ->string($classMetadata->propertyMetadata('name')->getMetadata('name'))
                    ->isEqualTo('fullName')
                ->string($classMetadata->propertyMetadata('addresses')->getMetadata('type'))
                    ->isEqualTo('ArraySet')
                ->string($classMetadata->propertyMetadata('addresses')->getMetadata('of'))
                    ->isEqualTo('Cubiche\Core\Metadata\Tests\Fixtures\Address')
        ;
    }

    /**
     * Test HasMetadataFor method.
     */
    public function testHasMetadataFor()
    {
        $this
            ->given($factory = $this->createFactory())
            ->when($metadatas = $factory->getAllMetadata())
            ->then()
                ->boolean($classMetadata = $factory->hasMetadataFor(Address::class))
                    ->isTrue()
        ;
    }

    /**
     * Test SetMetadataFor method.
     */
    public function testSetMetadataFor()
    {
        $this
            ->given($factory = $this->createFactory())
            ->then()
                ->boolean($classMetadata = $factory->hasMetadataFor(Address::class))
                    ->isFalse()
                ->and()
                ->when($factory->setMetadataFor(Address::class, new ClassMetadata(Address::class)))
                ->then()
                    ->boolean($classMetadata = $factory->hasMetadataFor(Address::class))
                        ->isTrue()
        ;
    }
}
