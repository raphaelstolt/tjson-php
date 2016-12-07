<?php

namespace Stolt\Tjson\DataTypes;

class UnsignedInteger
{
    const POSTFIX = ':u';

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
        if ($value > (2 ** 64) - 1) {
            $message = 'Oversized integer value.';
            throw new InvalidIntegerRange($message);
        }

        if ($value < 0) {
            $message = 'Undersized integer value.';
            throw new InvalidIntegerRange($message);
        }

        return $value;
    }
}
