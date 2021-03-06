<?php

/**
 * Curry function
 *
 * curry :: ((a, b), c) -> a -> b -> c
 * @package bingo-functional
 * @author Lochemem Bruno Michael
 * @license Apache 2.0
 */

namespace Chemem\Bingo\Functional\Algorithms;

use Chemem\Bingo\Functional\Algorithms as A;

const curry = "Chemem\\Bingo\\Functional\\Algorithms\\curry";

function curry(callable $fn, $required = true) : callable
{
    $func = new \ReflectionFunction($fn);

    return A\curryN(
        $required === true ?
            $func->getNumberOfRequiredParameters() :
            $func->getNumberOfParameters(),
        $fn
    );
}
