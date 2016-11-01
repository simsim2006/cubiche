<?php
/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\Core\Metadata\Tests\Fixtures\Metadata\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Property class.
 *
 * @author Ivannis Suárez Jerez <ivannis.suarez@gmail.com>
 *
 * @Annotation
 * @Target("PROPERTY")
 */
final class Property extends Annotation
{
    /**
     * @var string
     */
    public $projectionName;
}
