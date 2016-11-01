<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures\WriteModel\SubFolder;

use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\Annotations as ES;

/**
 * Department class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @ES\Projection(targetClass="\DDD\UI\ReadModel\Department")
 */
class Department
{
    /**
     * @var int
     * @ES\Property(projectionName="departmentId")
     */
    protected $id;

    /**
     * @var string
     * @ES\Property(projectionName="departmentName")
     */
    protected $name;

    /**
     * Department constructor.
     *
     * @param int    $id
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}
