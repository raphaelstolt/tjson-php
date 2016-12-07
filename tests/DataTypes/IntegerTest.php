<?php

namespace Stolt\Tjson\Tests\DataTypes;

use PHPUnit_Framework_TestCase as PHPUnit;
use Stolt\Tjson\DataTypes\SignedInteger;
use Stolt\Tjson\DataTypes\UnsignedInteger;

class IntegerTest extends PHPUnit
{
    /**
     * @test
     */
    public function keysArePosfixedAsExpected()
    {
        $this->assertEquals(
            'signed-integer:i',
            (new SignedInteger())->postfixed('signed-integer')
        );
        $this->assertEquals(
            'unsigned-integer:u',
            (new UnsignedInteger())->postfixed('unsigned-integer')
        );
    }
}
