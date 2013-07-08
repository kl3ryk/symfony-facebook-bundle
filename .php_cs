<?php

use Symfony\CS\Config\Config;
use Symfony\CS\Finder\DefaultFinder;

$blacklistedDirectories = array_fill_keys(['vendor'], true);
$whitelistedDirectories = array_filter(glob('*'), function ($dir) use (&$blacklistedDirectories) {
    return is_dir($dir) && !isset($blacklistedDirectories[$dir]);
});

// whitelist prevails over blacklist
$finder = DefaultFinder::create()->in($whitelistedDirectories);

return Config::create()->fixers([

    // Code must use 4 spaces for indenting, not tabs.
    'indentation',

    // All PHP files must use the Unix LF (linefeed) line ending.
    'linefeed',

    // Remove trailing whitespace at the end of lines.
    'trailing_spaces',

    // Unused use statements must be removed.
    'unused_use',

    // All items of the @param phpdoc tags must be aligned vertically.
    // 'phpdoc_params',

    // Opening braces for classes, interfaces, traits and methods must go on
    // the next line, and closing braces must go on the next line after the
    // body. Opening braces for control structures must go on the same line,
    // and closing braces must go on the next line after the body.
    'braces',

    // Removes extra empty lines.
    'extra_empty_lines',

    // An empty line feed should precede a return statement.
    'return',

    // Include and file path should be devided with single space. File path
    // should not be placed under brackets.
    'include',

    // The closing tag MUST be omitted from files containing only PHP.
    'php_closing_tag',

    // Visibility must be declared on all properties and methods; abstract and
    // final must be declared before the visibility; static must be declared
    // after the visibility.
    'visibility',

    // PHP code must use the long tags or the short-echo tags; it must not use
    // the other tag variations.
    'short_tag',

    // Classes must be in a path that matches their namespace, be at least one
    // namespace deep, and the class name should match the file name.
    'psr0',

    // A single space should be between: the closing brace and the control, the
    // control and the opening parenthese, the closing parenthese and the
    // opening brace.
    'controls_spaces',

    // The keyword elseif should be used instead of else if so that all control
    // keywords looks like single words.
    'elseif',

    // A file must always end with an empty line feed.
    'eof_ending',

])->finder($finder);
