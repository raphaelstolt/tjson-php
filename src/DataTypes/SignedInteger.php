<?php

namespace Stolt\Tjson\DataTypes;

use Stolt\Tjson\Exceptions\InvalidIntegerRange;

class SignedInteger
{
    const POSTFIX = ':i';

    /**
     * @param  string $key The key to postfix.
     * @return string
     */
    public function postfixed($key)
    {
        return $key . self::POSTFIX;
    }

    /**
     * @param  string $value The value to guard.
     * @throws InvalidIntegerRange
     * @return integer
     */
    public function guard($value)
    {
        if ($value > (2 ** 63) - 1) {
            $message = 'Oversized integer value.';
            throw new InvalidIntegerRange($message);
        }

        if ($value < (-1 * (2 ** 63))) {
            $message = 'Undersized integer value.';
            throw new InvalidIntegerRange($message);
        }

        return $value;
    }
}
