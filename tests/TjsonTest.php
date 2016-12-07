<?php

namespace Stolt\Tjson\Tests;

use JsonStreamingParser\Listener\GeoJsonListener;
use Stolt\Tjson\Exceptions\InvalidIntegerRange;
use Stolt\Tjson\Exceptions\InvalidJson;
use Stolt\Tjson\Exceptions\InvalidTimestamp;
use Stolt\Tjson\Exceptions\NonUtf8Content;
use Stolt\Tjson\Exceptions\NoToplevelObject;
use Stolt\Tjson\Exceptions\UnsupportedListener;
use Stolt\Tjson\Listeners\Json as JsonListener;
use Stolt\Tjson\Listeners\Tjson as TjsonListener;
use Stolt\Tjson\Tests\TestCase;
use Stolt\Tjson\Tjson;

class TjsonTest extends TestCase
{
    /**
     * @test
     * @dataProvider knownListenerProvider
     */
    public function listenerAreKnown($listener)
    {
        $method = new \ReflectionMethod(
          'Stolt\Tjson\Tjson',
          'guardListener'
        );
        $method->setAccessible(true);
        $method->invoke(new Tjson(), $listener);
    }

    /**
     * @test
     */
    public function unknownListenerTypesThrowsExpectedException()
    {
        $this->expectException(UnsupportedListener::class);

        $method = new \ReflectionMethod(
          'Stolt\Tjson\Tjson',
          'guardListener'
        );
        $method->setAccessible(true);
        $method->invoke(new Tjson(), new GeoJsonListener());
    }

    /**
     * @return array
     */
    public function knownListenerProvider()
    {
        return [
            'listener_json' => [
                'listener' => new JsonListener(),
            ],
            'listener_tjson' => [
                'listener' => new TjsonListener(),
            ],
        ];
    }

    /**
     * @test
     */
    public function throwsExceptionOnNonUtf8Tjson()
    {
        $this->expectException(NonUtf8Content::class);
        (new Tjson())->toJson(
            mb_convert_encoding('some string äüö', 'ISO-8859-1')
        );
    }

    /**
     * @test
     */
    public function throwsExceptionOnInvalidJson()
    {
        $this->expectException(InvalidJson::class);
        (new Tjson())->fromJson('{"Missing colon" null}');
    }

    /**
     * @test
     */
    public function throwsExceptionOnNonRootObject()
    {
        $this->expectException(NoToplevelObject::class);

        $json = '["No toplevel arrays in TJSON!"]';

        (new Tjson())->toJson($json);
    }

    /**
     * @test
     */
    public function stringsArePostfixed()
    {
        $json = '{"hello-string": "I\'m a string!"}';
        $expectTjson = '{"hello-string:s": "I\'m a string!"}';

        $this->assertJsonStringEqualsJsonString(
            $expectTjson,
            (new Tjson())->fromJson($json)
        );
    }

    /**
     * @test
     */
    public function validTimestampsArePostfixed()
    {
        $json = '{"hello-timestamp": "2016-10-02T07:31:51Z"}';
        $expectTjson = '{"hello-timestamp:t": "2016-10-02T07:31:51Z"}';

        $this->assertJsonStringEqualsJsonString(
            $expectTjson,
            (new Tjson())->fromJson($json)
        );
    }

    /**
     * @test
     */
    public function invalidTimestampsThrowAnException()
    {
        $json = '{"hello-timestamp": "2016-10-02T07:31:51R"}';

        $this->expectException(InvalidTimestamp::class);

        (new Tjson())->fromJson($json);
    }

    /**
     * @test
     */
    public function unsignedIntegersArePostfixed()
    {
        $unsignedInteger = 2 ** 63;
        $json = '{"hello-signed-int": "' . $unsignedInteger . '"}';
        $expectTjson = '{"hello-signed-int:u": "' . $unsignedInteger . '"}';

        $this->assertJsonStringEqualsJsonString(
            $expectTjson,
            (new Tjson())->fromJson($json)
        );
    }

    /**
     * @test
     */
    public function signedIntegersArePostfixed()
    {
        $json = '{"hello-signed-int": "42"}';
        $expectTjson = '{"hello-signed-int:i": "42"}';

        $this->assertJsonStringEqualsJsonString(
            $expectTjson,
            (new Tjson())->fromJson($json)
        );
    }

    /**
     * @test
     * @dataProvider invalidSignedIntegerProvider
     */
    public function invalidSignedIntegerThrowAnException($integer)
    {
        $json = '{"hello-signed-int": "' . $integer . '"}';

        $this->expectException(InvalidIntegerRange::class);

        (new Tjson())->fromJson($json);
    }

    /**
     * @return array
     */
    public function invalidSignedIntegerProvider()
    {
        return [
            'oversized integer' => [
                'integer' => 2 ** 64,
            ],
            'undersized integer' => [
                'integer' => (-1 * ((2 ** 63) + 4)),
            ],
        ];
    }

    /**
     * @test
     */
    public function floatsAreNotTagged()
    {
        $json = '{"i-am-a-float": 0.42}';
        $expectTjson = '{"i-am-a-float": 0.42}';

        $this->assertJsonStringEqualsJsonString(
            $expectTjson,
            (new Tjson())->fromJson($json)
        );
    }
}
