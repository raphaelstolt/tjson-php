<?php

namespace Stolt\Tjson\Tests\DataTypes;

use PHPUnit_Framework_TestCase as PHPUnit;
use Stolt\Tjson\DataTypes\Text;

class TextTest extends PHPUnit
{
    /**
     * @test
     */
    public function keyIsPosfixedAsExpected()
    {
        $this->assertEquals(
            'some-key:s',
            (new Text())->postfixed('some-key')
        );
    }
}
