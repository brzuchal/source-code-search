<?php
$finder = \PhpCsFixer\Finder::create()
   ->in('src')
   ->in('test')
   ->exclude('Fixtures')
;
return \PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        '@PHP71Migration:risky' => true,
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_align' => false,
        'phpdoc_summary' => false,
        'yoda_style' => false,
        'line_ending' => true,
        'array_syntax' => [
            'syntax' => 'short'
        ],
    ])
    ->setFinder($finder)
;