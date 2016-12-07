<?php

namespace Stolt\Tjson;

use JsonStreamingParser\Listener;
use JsonStreamingParser\Parser;
use JsonStreamingParser\ParsingError;
use Stolt\Tjson\Exceptions\InvalidJson;
use Stolt\Tjson\Exceptions\NonUtf8Content;
use Stolt\Tjson\Exceptions\UnsupportedListener;
use Stolt\Tjson\Listeners\Json as JsonListener;
use Stolt\Tjson\Listeners\Tjson as TjsonListener;

class Tjson
{
    /**
     * @var resource
     */
    private $stream;

    /**
     * @param  string $tjson
     * @throws Stolt\Tjson\Exceptions\NonUtf8Content
     * @return string
     */
    public function toJson($tjson)
    {
        if (mb_detect_encoding($tjson, 'UTF-8', true) !== 'UTF-8') {
            $message = 'Cannot operate on non utf-8 encoded Tjson.';
            throw new NonUtf8Content($message);
        }

        $tjsonListener = new TjsonListener();

        $parser = $this->getStreamParser($tjson, $tjsonListener);
        $parser->parse();

        $this->closeStream();
    }

    /**
     * @param  string $json The TJSON/JSON to parse.
     * @param  JsonStreamingParser\Listener $listener The listener type for parsing.
     * @throws InvalidArgumentException
     * @return JsonStreamingParser\Parser
     */
    private function getStreamParser($json, Listener $listener)
    {
        self::guardListener($listener);

        $this->stream = fopen('php://memory', 'r+');
        fwrite($this->stream, $json);
        rewind($this->stream);

        return new Parser($this->stream, $listener);
    }

    /**
     * @return void
     */
    private function closeStream()
    {
        fclose($this->stream);
    }

    /**
     * @param  JsonStreamingParser\Listener $listener The listener to guard.
     * @throws UnsupportedListener
     * @return void
     */
    private function guardListener(Listener $listener)
    {
        if (!$listener instanceof JsonListener && !$listener instanceof TjsonListener) {
            $message = sprintf(
                "Unsupported listener '%s' provided.",
                get_class($listener)
            );
            throw new UnsupportedListener($message);
        }
    }

    /**
     * @param  string $json
     * @throws Stolt\Tjson\Exceptions\InvalidJson
     * @return string
     */
    public function fromJson($json)
    {
        try {
            $jsonListener = new JsonListener();

            $parser = $this->getStreamParser($json, $jsonListener);
            $parser->parse();
            $this->closeStream();

            return $jsonListener->getTjson();
        } catch (ParsingError $e) {
            $this->closeStream();
            throw new InvalidJson($e->getMessage());
        }
    }
}
