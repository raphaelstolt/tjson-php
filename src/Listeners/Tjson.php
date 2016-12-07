<?php
namespace Stolt\Tjson\Listeners;

use JsonStreamingParser\Listener;
use Stolt\Tjson\Exceptions\NoToplevelObject;

class Tjson implements Listener
{
    /**
     * @var string
     */
    protected $json;
    /**
     * @var integer
     */
    protected $level;

    /**
     * @return string
     */
    public function getJson()
    {
        return $this->json;
    }

    public function startDocument()
    {
        $this->level = 0;
        echo '__STARTING_DOCUMENT__' . PHP_EOL;
    }

    public function endDocument()
    {
        echo '__ENDING_DOCUMENT__' . PHP_EOL;
    }

    public function startObject()
    {
        ++$this->level;
        echo '__STARTING_OBJECT__' . PHP_EOL;
    }

    public function endObject()
    {
        echo '__ENDING_OBJECT__' . PHP_EOL;
    }

    public function startArray()
    {
        if ($this->level === 0) {
            throw new NoToplevelObject();
        }
        echo '__STARTING_ARRAY__' . PHP_EOL;
    }

    public function endArray()
    {
        echo '__ENDING_ARRAY__' . PHP_EOL;
    }

    /**
     * @param string $key
     */
    public function key($key)
    {
        echo 'KEY: ' . $key . PHP_EOL;
    }

    /**
     * Value may be a string, integer, boolean, null
     * @param mixed $value
     */
    public function value($value)
    {
        echo 'VALUE: ' . $value . PHP_EOL;
    }

    public function whitespace($whitespace)
    {
    }
}
