<?php

/**
 * This file is part of the Cubiche/EventSourcing component.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Tests\Units\Migrations\Manager;

use Cubiche\Domain\EventSourcing\Migrations\Manager\MigrationManager;
use Cubiche\Domain\EventSourcing\Migrations\Migration;
use Cubiche\Domain\EventSourcing\Migrations\Store\InMemoryMigrationStore;
use Cubiche\Domain\EventSourcing\Tests\Fixtures\PostEventSourced;
use Cubiche\Domain\EventSourcing\Tests\Units\TestCase;
use Cubiche\Domain\EventSourcing\Versioning\Version;

/**
 * MigrationManagerTests class.
 *
 * Generated by TestGenerator on 2016-08-31 at 15:14:55.
 */
class MigrationManagerTests extends TestCase
{
    /**
     * @return MigrationManager
     */
    protected function createManager()
    {
        require_once __DIR__.'/../../../Fixtures/BlogEventSourced.php';

        $aggregates = [PostEventSourced::class, \BlogEventSourced::class];

        $store = new InMemoryMigrationStore();
        $store->persist(new Migration($aggregates, Version::fromString('0.2.0'), new \DateTime()));
        $store->persist(new Migration($aggregates, Version::fromString('0.1.0'), new \DateTime()));

        $manager = new MigrationManager($store, __DIR__.'/../../../Fixtures/Migrations');
        $manager->registerMigrationsFromDirectory();

        return $manager;
    }

    /**
     * Test CurrentVersion method.
     */
    public function testCurrentVersion()
    {
        $this
            ->given($manager = $this->createManager())
            ->then()
                ->object($manager->currentVersion())
                    ->isEqualTo(Version::fromString('0.2.0'))
        ;
    }

    /**
     * Test LatestVersion method.
     */
    public function testLatestVersion()
    {
        $this
            ->given($manager = $this->createManager())
            ->then()
                ->object($manager->latestVersion())
                    ->isEqualTo(Version::fromString('1.1.0'))
        ;
    }

    /**
     * Test AvailableVersions method.
     */
    public function testAvailableVersions()
    {
        $this
            ->given($manager = $this->createManager())
            ->then()
                ->array($manager->availableVersions())
                    ->isEqualTo([
                        Version::fromString('0.1.0'),
                        Version::fromString('0.2.0'),
                        Version::fromString('1.0.0'),
                        Version::fromString('1.1.0'),
                    ])
        ;
    }

    /**
     * Test MigratedVersions method.
     */
    public function testMigratedVersions()
    {
        $this
            ->given($manager = $this->createManager())
            ->then()
                ->array($manager->migratedVersions())
                    ->isEqualTo([Version::fromString('0.2.0'), Version::fromString('0.1.0')])
        ;
    }

    /**
     * Test NumberOfMigratedVersions method.
     */
    public function testNumberOfMigratedVersions()
    {
        $this
            ->given($manager = $this->createManager())
            ->then()
                ->integer($manager->numberOfMigratedVersions())
                    ->isEqualTo(2)
        ;
    }

    /**
     * Test HasMigratedVersion method.
     */
    public function testHasMigratedVersion()
    {
        $this
            ->given($manager = $this->createManager())
            ->then()
                ->boolean($manager->hasMigratedVersion(Version::fromString('0.1.0')))
                    ->isTrue()
                ->boolean($manager->hasMigratedVersion(Version::fromString('1.0.0')))
                    ->isFalse()
        ;
    }

    /**
     * Test MigrationsToExecute method.
     */
    public function testMigrationsToExecute()
    {
        $this
            ->given($manager = $this->createManager())
            ->when($migrations = $manager->migrationsToExecute(Version::fromString('1.0.0')))
            ->then()
                ->sizeOf($migrations)
                    ->isEqualTo(2)
                ->integer($migrations[0]->version()->compareTo($migrations[1]->version()))
                    ->isEqualTo(-1)
        ;
    }

    /**
     * Test RegisterMigration method.
     */
    public function testRegisterMigration()
    {
        $this
            ->given($manager = $this->createManager())
            ->then()
                ->exception(function () use ($manager) {
                    $manager->registerMigration([], Version::fromString('0.2.0'));
                })
                ->isInstanceOf(\RuntimeException::class)
        ;
    }

    /**
     * Test RegisterMigrationsFromDirectory method.
     */
    public function testRegisterMigrationsFromDirectory()
    {
        $this
            ->given(
                $manager = new MigrationManager(
                    new InMemoryMigrationStore(),
                    __DIR__.'/../../../Fixtures/InvalidMigrations/WithoutMigrationClass'
                )
            )
            ->then()
            ->exception(function () use ($manager) {
                $manager->registerMigrationsFromDirectory();
            })
            ->isInstanceOf(\UnexpectedValueException::class)
        ;

        $this
            ->given(
                $manager = new MigrationManager(
                    new InMemoryMigrationStore(),
                    __DIR__.'/../../../Fixtures/InvalidMigrations/WithInvalidName'
                )
            )
            ->then()
            ->exception(function () use ($manager) {
                $manager->registerMigrationsFromDirectory();
            })
            ->isInstanceOf(\RuntimeException::class)
        ;
    }
}