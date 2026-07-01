<?php

// Formatter config — the "Prettier" of PHP.
//   composer format         -> rewrite files
//   composer format:check   -> check only (CI)

$finder = PhpCsFixer\Finder::create()
    ->in([__DIR__ . '/app', __DIR__ . '/public'])
    ->name('*.php');

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,                          // PHP standard style: 4-space indent, etc.
        'no_extra_blank_lines' => true,            // collapse 2+ blank lines into one (like Prettier)
        'array_syntax' => ['syntax' => 'short'],   // [] not array()
        'single_quote' => true,                    // '' unless interpolating
        'no_unused_imports' => true,               // drop unused `use` lines
        'ordered_imports' => true,                 // alphabetise `use` lines
        'trailing_comma_in_multiline' => true,
    ])
    ->setFinder($finder);
