<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Specification\Constraint;

use Cubiche\Core\Specification\SpecificationVisitorInterface;

/**
 * Same Specification Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class Same extends BinaryConstraintOperator
{
    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationInterface::evaluate()
     */
    public function evaluate($value)
    {
        return $this->left()->apply($value) === $this->right()->apply($value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationInterface::acceptSpecificationVisitor()
     */
    public function acceptSpecificationVisitor(SpecificationVisitorInterface $visitor)
    {
        return $visitor->visitSame($this);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Cubiche\Core\Specification\SpecificationInterface::not()
     */
    public function not()
    {
        return new NotSame($this->left(), $this->right());
    }
}