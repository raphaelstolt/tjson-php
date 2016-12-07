<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__);

$rules = [
    'psr0' => false,
    '@PSR2' => true,
    'array_syntax' => ['syntax' => 'short'],
    'phpdoc_order' => true,
    'ordered_imports' => true,
    'method_argument_space' => [
        'ensure_fully_multiline' => true,
        'keep_multiple_spaces_after_comma' => false,
    ],
];

$cacheDir = getenv('TRAVIS') ? getenv('HOME') . '/.php-cs-fixer' : __DIR__;

return Config::create()
    ->setRules($rules)
    ->setFinder($finder)
    ->setCacheFile($cacheDir . '/.php_cs.cache');
