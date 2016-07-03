<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Core\Visitor\Tests\Fixtures;

use Cubiche\Core\Visitor\Visitor;

/**
 * Smart Expression to String Converter Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class SmartExpressionToStringConverter extends Visitor
{
    /**
     * @param Operator $op
     *
     * @return string
     */
    public function visitOperator(Operator $op, $parentOperator = null)
    {
        $currentOperator = $op->operator();
        $expression = $op->firstOperand()->accept($this, $currentOperator).
            $currentOperator.$op->secondOperand()->accept($this, $currentOperator);

        return $this->requireParentheses($currentOperator, $parentOperator) ? '('.$expression.')' : $expression;
    }

    /**
     * @param Value $value
     *
     * @return string
     */
    public function visitValue(Value $value)
    {
        return (string) $value->value();
    }

    /**
     * @param Variable $variable
     *
     * @return string
     */
    public function visitVariable(Variable $variable)
    {
        return $variable->name();
    }

    /**
     * @param string $currentOperator
     * @param string $parentOperator
     *
     * @return bool
     */
    protected function requireParentheses($currentOperator, $parentOperator)
    {
        return $currentOperator === '+' && $parentOperator === '*';
    }
}
