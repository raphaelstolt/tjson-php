<?php

namespace Stolt\Tjson\DataTypes;

class Text
{
    const POSTFIX = ':s';

    /**
     * @param  string $key The key to postfix.
     * @return string
     */
    public function postfixed($key)
    {
        return $key . self::POSTFIX;
    }
}
