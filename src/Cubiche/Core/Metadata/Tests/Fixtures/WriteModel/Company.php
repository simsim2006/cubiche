<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures\WriteModel;

use Cubiche\Core\Metadata\Tests\Fixtures\Metadata\Annotations as ES;

/**
 * Company class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 * @ES\Projection(targetClass="DDD\UI\ReadModel\Company")
 */
class Company
{
    /**
     * @var int
     * @ES\Property(projectionName="companyId")
     */
    protected $id;

    /**
     * @var string
     * @ES\Property(projectionName="companyName")
     */
    protected $name;

    /**
     * Company constructor.
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
