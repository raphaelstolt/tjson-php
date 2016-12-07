<?php

namespace Stolt\Tjson\DataTypes;

use Stolt\Tjson\Exceptions\InvalidTimestamp;

class Timestamp
{
    const POSTFIX = ':t';

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
     * @throws InvalidTimestamp
     * @return string
     */
    public function guard($value)
    {
        $value = trim($value);
        $timestampRegex = '/\A\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z\z/';

        if (preg_match($timestampRegex, $value) === 0) {
            $message = sprintf(
                "Timestamp '%s' is invalid.",
                $value
            );
            throw new InvalidTimestamp($message);
        }

        return $value;
    }
}
