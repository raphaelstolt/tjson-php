<?php

namespace Stolt\Tjson\Tests\DataTypes;

use PHPUnit_Framework_TestCase as PHPUnit;
use Stolt\Tjson\DataTypes\Timestamp;
use Stolt\Tjson\Exceptions\InvalidTimestamp;

class TimestampTest extends PHPUnit
{
    /**
     * @test
     */
    public function keyIsPosfixedAsExpected()
    {
        $this->assertEquals(
            'hello-timestamp:t',
            (new Timestamp())->postfixed('hello-timestamp')
        );
    }

    /**
     * @test
     */
    public function validTimestampDoesNotCauseAnException()
    {
        $this->assertEquals(
            '2016-10-02T07:31:51Z',
            (new Timestamp())->guard('         2016-10-02T07:31:51Z')
        );
    }

    /**
     * @test
     * @param mixed $timestamp A invalid timestamp.
     * @dataProvider invalidTimestampProvider
     */
    public function invalidTimestampsThrowExpectedException($timestamp)
    {
        $this->expectException(InvalidTimestamp::class);
        (new Timestamp())->guard($timestamp);
    }

    /**
     * @return array
     */
    public function invalidTimestampProvider()
    {
        return [
            'invalid_null' => [
                'timestamp' => null,
            ],
            'invalid_string' => [
                'timestamp' => 'abcdef',
            ],
            'invalid_time_zone_identifier' => [
                'timestamp' => '2016-10-02T07:31:51R',
            ],
        ];
    }
}
