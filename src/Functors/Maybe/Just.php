<?php

/**
 * Just type functor
 *
 * @package bingo-functional
 * @author Lochemem Bruno Michael
 * @license Apache 2.0
 */

namespace Chemem\Bingo\Functional\Functors\Maybe;

use Chemem\Bingo\Functional\Common\Functors\FunctorInterface;

final class Just extends Maybe implements FunctorInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */

    public function isJust() : bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */

    public function isNothing() : bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */

    public function getJust()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */

    public function getNothing()
    {
        return null;
    }

    /**
     * @inheritdoc
     */

    public function flatMap(callable $fn)
    {
        return $fn($this->getJust());
    }

    /**
     * @inheritdoc
     */

    public function getOrElse($default)
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */

    public function map(callable $fn) : FunctorInterface
    {
        return new static($fn($this->getJust()));
    }

    /**
     * @inheritdoc
     */

    public function filter(callable $fn) : Maybe
    {
        return $fn($this->getJust()) ?
            new static($this->getJust()) :
            new Nothing();
    }

    /**
     * @inheritdoc
     */

    public function orElse(Maybe $value) : Maybe
    {
        return !is_null($this->value) || isset($this->value) ?
            new static($this->getJust()) :
            new static($value->getJust());
    }
}
