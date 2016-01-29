<?php

/**
 * This file is part of the Cubiche package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Cubiche\Domain\Collections\Tests\Specification;

use Cubiche\Domain\Model\Tests\TestCase;
use Cubiche\Domain\Collections\Specification\Criteria;
use Cubiche\Domain\Collections\Specification\Selector\Value;
use Cubiche\Domain\Collections\Specification\Selector\Key;
use Cubiche\Domain\Collections\Specification\Selector\Property;
use Cubiche\Domain\Collections\Specification\Selector\Method;
use Cubiche\Domain\Collections\Specification\Selector\SelfSelector;
use Cubiche\Domain\Collections\Specification\Constraint\GreaterThan;
use Cubiche\Domain\Collections\Specification\Quantifier\All;

/**
 * Criteria Test Class.
 *
 * @author Karel Osorio Ramírez <osorioramirez@gmail.com>
 */
class CriteriaTest extends TestCase
{
    /**
     * @test
     */
    public function testFalse()
    {
        $false = Criteria::false();
        $this->assertInstanceOf(Value::class, $false);
        $this->assertEquals(false, $false->value());
    }

    /**
     * @test
     */
    public function testTrue()
    {
        $true = Criteria::true();
        $this->assertInstanceOf(Value::class, $true);
        $this->assertEquals(true, $true->value());
    }

    /**
     * @test
     */
    public function testNull()
    {
        $null = Criteria::null();
        $this->assertInstanceOf(Value::class, $null);
        $this->assertEquals(null, $null->value());
    }

    /**
     * @test
     */
    public function testKey()
    {
        $key = Criteria::key('foo');
        $this->assertInstanceOf(Key::class, $key);
        $this->assertEquals('foo', $key->name());
    }

    /**
     * @test
     */
    public function testProperty()
    {
        $property = Criteria::property('foo');
        $this->assertInstanceOf(Property::class, $property);
        $this->assertEquals('foo', $property->name());
    }

    /**
     * @test
     */
    public function testMethod()
    {
        $method = Criteria::method('foo');
        $this->assertInstanceOf(Method::class, $method);
        $this->assertEquals('foo', $method->name());
        $this->assertEmpty($method->args());
    }

    /**
     * @test
     */
    public function testSelf()
    {
        $self = Criteria::self();
        $this->assertInstanceOf(SelfSelector::class, $self);
    }

    /**
     * @test
     */
    public function testGt()
    {
        $gt = Criteria::gt(5);
        $this->assertInstanceOf(GreaterThan::class, $gt);

        $this->assertTrue($gt->evaluate(6));
        $this->assertFalse($gt->evaluate(5));
        $this->assertFalse($gt->evaluate(4));
    }

    /**
     * @test
     */
    public function testAll()
    {
        $all = Criteria::all(Criteria::gt(5));

        $this->assertInstanceOf(All::class, $all);
        $this->assertTrue($all->evaluate(array(6, 7, 8, 9)));
        $this->assertTrue($all->evaluate(array()));
        $this->assertTrue($all->evaluate(6));
        $this->assertFalse($all->evaluate(array(6, 7, 8, 9, 5)));
    }
}
