<?php
namespace Stolt\Tjson\Listeners;

use JsonStreamingParser\Listener;
use Stolt\Tjson\DataTypes\SignedInteger;
use Stolt\Tjson\DataTypes\Text;
use Stolt\Tjson\DataTypes\Timestamp;
use Stolt\Tjson\DataTypes\UnsignedInteger;

class Json implements Listener
{
    /**
     * @var array
     */
    protected $tjson;
    /**
     * @var integer
     */
    protected $level;
    /**
     * @var string
     */
    protected $key;

    /**
     * @return string
     */
    public function getTjson()
    {
        return json_encode($this->tjson);
    }

    public function startDocument()
    {
        $this->level = 0;
        //echo '__STARTING_DOCUMENT__' . PHP_EOL;
    }

    public function endDocument()
    {
        //echo '__ENDING_DOCUMENT__' . PHP_EOL;
    }

    public function startObject()
    {
        ++$this->level;
        //echo '__STARTING_OBJECT__' . PHP_EOL;
    }

    public function endObject()
    {
        //echo '__ENDING_OBJECT__' . PHP_EOL;
    }

    public function startArray()
    {
        if ($this->level === 0) {
            echo 'OH_NO';
        }
        //echo '__STARTING_ARRAY__' . PHP_EOL;
    }

    public function endArray()
    {
        //echo '__ENDING_ARRAY__' . PHP_EOL;
    }

    /**
     * @param string $key
     */
    public function key($key)
    {
        $this->key = $key;
    }

    /**
     * Value may be a string, integer, boolean, null
     * @param mixed $value
     */
    public function value($value)
    {
        if ($this->isTimestampish($value)) {
            $key = (new Timestamp())->postfixed($this->key);
            $this->tjson[$key] = (new Timestamp())->guard($value);
        } elseif (is_float($value)) {
            $this->tjson[$this->key] = $value;
        } elseif (is_numeric($value) && $value >= (2 ** 63) - 1 && $value <= (2 ** 64) - 1) {
            $key = (new UnsignedInteger())->postfixed($this->key);
            $this->tjson[$key] = (new UnsignedInteger())->guard($value);
        } elseif (is_numeric($value)) {
            $key = (new SignedInteger())->postfixed($this->key);
            $this->tjson[$key] = (new SignedInteger())->guard($value);
        } elseif (is_string($value)) {
            $key = (new Text())->postfixed($this->key);
            $this->tjson[$key] = $value;
        }
    }

    /**
     * @param  mixed $value
     * @return boolean
     */
    private function isTimestampish($value)
    {
        $timestampRegex = '/\A\d{4}-\d{2}-\d{2}/';
        return preg_match($timestampRegex, $value) === 1;
    }

    public function whitespace($whitespace)
    {
    }
}
