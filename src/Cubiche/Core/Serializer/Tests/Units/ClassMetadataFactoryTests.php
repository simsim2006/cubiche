<?php

/**
 * This file is part of the Cubiche/Serializer component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Serializer\Tests\Units;

use Cubiche\Core\Metadata\Cache\FileCache;
use Cubiche\Core\Metadata\ClassMetadataFactory;
use Cubiche\Core\Metadata\Locator\DefaultFileLocator;
use Cubiche\Core\Metadata\Tests\Fixtures\Driver\XmlDriver;
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

        $this->cacheDirectory = __DIR__.'/Cache';
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
     * @return XmlDriver
     */
    protected function createDriver()
    {
        $mappingDirectory = __DIR__.'/../Fixtures/mapping';

        return new XmlDriver(
            new DefaultFileLocator([
                $mappingDirectory => 'Cubiche\Core\Serializer\Tests',
            ])
        );
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
     * Remove directory when the directory is not empty.
     *
     * @param string $dir
     */
    protected function rmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($dir.'/'.$object) == 'dir') {
                        $this->rmdir($dir.'/'.$object);
                    } else {
                        unlink($dir.'/'.$object);
                    }
                }
            }

            reset($objects);
            rmdir($dir);
        }
    }
}
