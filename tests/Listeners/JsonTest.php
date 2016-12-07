<?php

namespace Stolt\Tjson\Tests\Listener;

use JsonStreamingParser\Parser;
use PHPUnit_Framework_TestCase as PHPUnit;
use Stolt\Tjson\Listeners\Json;

class JsonTest extends PHPUnit
{
    /**
     * @test
     */
    public function transformJsonIntoExpectedTjson()
    {
        $this->markTestSkipped('Wip');
        $listener = new Json();

        $json = <<<CONTENT
[
  {
    "id": 5000242,
    "title": "Title 5000242",
    "detailPageUrl": "http://some.uri",
    "parentId": 0
  },
  {
    "id": 5001066,
    "title": "Title 5001066",
    "detailPageUrl": "http://some.uri",
    "parentId": 5000242,
    "items": [
      {
        "id": 50010661,
        "title": "Title 50010661"
      },
      {
        "id": 50010662,
        "title": "Title 50010662",
        "items": [
          {
            "id": 500106621,
            "title": "Title 500106621"
          }
        ]
      }
    ]
  },
  {
    "id": 5000678,
    "title": "Title 5000678",
    "detailPageUrl": "http://some.uri",
    "parentId": 5001066
  }
]
CONTENT;

        $jsonStream = fopen('php://memory', 'r+');
        fwrite($jsonStream, $json);
        rewind($jsonStream);

        try {
            $parser = new Parser($jsonStream, $listener);
            $parser->parse();
            fclose($jsonStream);
        } catch (\Exception $e) {
            fclose($jsonStream);
            throw $e;
        }

        $expectedTjson = <<<CONTENT
CONTENT;

        $this->assertEquals(
            $expectedTjson,
            $listener->getTjson()
        );
    }
}
