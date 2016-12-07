<?php

namespace Stolt\Tjson\Tests;

use \SplFileObject;
use PHPUnit_Framework_TestCase as PHPUnit;

class TestCase extends PHPUnit
{
    /**
     * @var string
     */
    protected $examplesDirectory;

    public function setUp()
    {
        $this->examplesDirectory = dirname(__FILE__)
            . DIRECTORY_SEPARATOR
            . 'examples';
    }

    /**
     * Turns the annotated TJSON examples into a PHPUnit data provider.
     *
     * @see    https://github.com/tjson/tjson-spec/blob/master/draft-tjson-examples.txt
     * @return array
     */
    public function exampleProvider()
    {
        $examples = [];
        $openExampleBlock = false;
        $example = [];
        $blockDelimiter = '-----';
        $exampleFile = $this->examplesDirectory
            . DIRECTORY_SEPARATOR
            . 'tjson.txt';
        $fileObject = new SplFileObject($exampleFile);

        $extractContent = function ($value) {
            list($key, $value) = explode('=', $value);
            return [
                trim($key) => str_replace('"', '', trim($value))
            ];
        };

        $snakecase = function ($string) {
            return str_replace([' ', '-'], ['_', ''], strtolower($string));
        };

        $transformExample = function (array $example) use ($extractContent, $snakecase) {
            $extract = $extractContent(array_shift($example));
            $name = $snakecase($extract['name']);

            $extract = $extractContent(array_shift($example));
            $description = $extract['description'];

            $extract = $extractContent(array_shift($example));
            $expectedResult = ($extract['result'] == 'success') ? true : false;

            $example = array_pop($example);

            return [
                $name => [
                    'description' => $description,
                    'expected_result' => $expectedResult,
                    'tjson' => $example,
                ]
            ];
        };

        $isOpeningBlock = function ($content, $openState) use ($blockDelimiter) {
            return $content === $blockDelimiter && $openState === false;
        };

        $isClosingBlock = function ($content, $openState) use ($blockDelimiter) {
            return $content === $blockDelimiter && $openState === true;
        };

        $isExampleContent = function ($content, $openState) use ($blockDelimiter) {
            return $openState === true
                && $content !== $blockDelimiter
                && $content !== '';
        };

        $isCommentedContent = function ($content) {
            return strpos($content, '#') === 0;
        };

        foreach ($fileObject as $line => $content) {
            $content = trim($content);

            if ($isCommentedContent($content)) {
                continue;
            }

            if ($isOpeningBlock($content, $openExampleBlock)) {
                $openExampleBlock = true;
                continue;
            }

            if ($isExampleContent($content, $openExampleBlock)) {
                $example[] = $content;
            }

            if ($isClosingBlock($content, $openExampleBlock)) {
                $example = $transformExample($example);
                $examples[array_keys($example)[0]] = array_values($example)[0];
                $openExampleBlock = false;
                $example = [];
            }
        }

        return $examples;
    }
}
