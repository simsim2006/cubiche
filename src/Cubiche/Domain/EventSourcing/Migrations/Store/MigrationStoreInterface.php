<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\EventSourcing\Migrations\Store;

use Cubiche\Domain\EventSourcing\Migrations\Migration;
use Cubiche\Domain\EventSourcing\Versioning\Version;

/**
 * MigrationStore interface.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 */
interface MigrationStoreInterface
{
    /**
     * @param Migration $migration
     */
    public function persist(Migration $migration);

    /**
     * @param Version $version
     *
     * @return bool
     */
    public function hasVersion(Version $version);

    /**
     * @return Migration[]
     */
    public function findAll();

    /**
     * @return Migration
     */
    public function getLast();

    /**
     * @return int
     */
    public function count();
}