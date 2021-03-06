<?php

use PHPUnit\Framework\TestCase;
use Chemem\Bingo\Functional\Functors\Maybe\{Maybe, Just, Nothing};

class MaybeTypeTest extends TestCase
{
    public function testMaybeTypeJustMethodReturnsJustType()
    {
        $val = Maybe::just(12);
        $this->assertInstanceOf(Just::class, $val);
    }

    public function testMaybeTypeNothingMethodReturnsNothingType()
    {
        $val = Maybe::nothing(12);
        $this->assertInstanceOf(Nothing::class, $val);
    }

    public function testMaybeTypeFromValueReturnsTypeFromValueDefinition()
    {
        $val = Maybe::fromValue(12);
        $another = Maybe::fromValue(12, 12);
        $yetAnother = Maybe::fromValue(null);
        $this->assertInstanceOf(Just::class, $val);
        $this->assertInstanceOf(Nothing::class, $another);
        $this->assertInstanceOf(Nothing::class, $yetAnother);
    }

    public function testMaybeJustTypeValueIsJust()
    {
        $val = Maybe::just(12);

        $this->assertTrue($val->isJust());
        $this->assertFalse($val->isNothing());
    }

    public function testMaybeNothingTypeValueIsNothing()
    {
        $val = Maybe::nothing();

        $this->assertTrue($val->isNothing());
        $this->assertFalse($val->isJust());
    }

    public function testMaybeLiftMethodChangesFunctionsToAcceptMaybeTypes()
    {
        $add = function (int $a, int $b) : int {
            return $a + $b;
        };

        $lifted = Maybe::lift($add);
        $this->assertEquals(
            $lifted(
                Maybe::just(1),
                Maybe::just(2)
            )
            ->getJust(),
            3
        );
    }

    public function testMaybeJustTypeGetJustMethodReturnsJustValue()
    {
        $val = Maybe::just(12);

        $this->assertEquals($val->getJust(), 12);
        $this->assertEquals($val->getNothing(), null);
    }

    public function testMaybeJustTypeFlatMapMethodReturnsNonEncapsulatedValue()
    {
        $val = Maybe::just(12)
            ->flatMap(
                function (int $a) : int {
                    return $a + 10;
                }
            );
        $this->assertEquals($val, 22);
    }

    public function testMaybeJustTypeMapMethodReturnsEncapsulatedValue()
    {
        $val = Maybe::just(12)
            ->map(
                function (int $a) : int {
                    return $a + 10;
                }
            );
        $this->assertInstanceOf(Just::class, $val);
    }

    public function testMaybeJustTypeFilterMethodReturnsEncapsulatedValueBasedOnPredicate()
    {
        $val = Maybe::just('foo')
            ->filter(
                function (string $str) : bool {
                    return is_string($str);
                }
            );
        $this->assertInstanceOf(Just::class, $val);
        $this->assertEquals($val->getJust(), 'foo');
    }

    public function testMaybeJustTypeReturnsNothingIfConditionEvaluatesToFalse()
    {
        $val = Maybe::just(12)
            ->filter(
                function (int $val) : bool {
                    return is_string($val);
                }
            );
        $this->assertInstanceOf(Nothing::class, $val);
        $this->assertEquals($val->getNothing(), null);
    }

    public function testMapFlatMapFilterMethodsHaveNoEffectOnNothingValue()
    {
        $val = Maybe::nothing()
            ->filter(
                function ($val = null) {
                    return is_null($val);
                }
            )
            ->map(
                function ($val = null) : string {
                    return "null";
                }
            )
            ->flatMap(
                function ($val = null) : string {
                    return "null";
                }
            );
        $this->assertEquals($val, null);
    }
}
