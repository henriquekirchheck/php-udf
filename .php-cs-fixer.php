<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$typesMap = [
  'T' => 'mixed',
  'TFixerInputConfig' => 'array',
  'TFixerComputedConfig' => 'array',
  'TFixer' => '\PhpCsFixer\AbstractFixer',
  '_PhpTokenKind' => 'int|string',
  '_PhpTokenArray' => 'array{0: int, 1: string}',
  '_PhpTokenArrayPartial' => 'array{0: int, 1?: string}',
  '_PhpTokenPrototype' => '_PhpTokenArray|string',
  '_PhpTokenPrototypePartial' => '_PhpTokenArrayPartial|string',
];

return (new Config())
  ->setParallelConfig(ParallelConfigFactory::detect()) // @TODO 4.0 no need to call this manually
  ->setUnsupportedPhpVersionAllowed(true)
  ->setRiskyAllowed(true)
  ->setIndent('  ')
  ->setRules([
    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,
    'general_phpdoc_annotation_remove' => ['annotations' => ['expectedDeprecation']], // one should use PHPUnit built-in method instead
    'modernize_strpos' => true, // needs PHP 8+ or polyfill
    'native_constant_invocation' => ['strict' => false], // strict:false to not remove `\` on low-end PHP versions for not-yet-known consts
    'no_useless_concat_operator' => false, // TODO switch back on when the `src/Console/Application.php` no longer needs the concat
    'numeric_literal_separator' => true,
    'phpdoc_order' => [
      'order' => [
        'type',
        'template',
        'template-covariant',
        'template-extends',
        'extends',
        'implements',
        'property',
        'method',
        'param',
        'return',
        'var',
        'assert',
        'assert-if-false',
        'assert-if-true',
        'throws',
        'author',
        'see',
      ],
    ],
    'phpdoc_to_param_type' => ['types_map' => $typesMap],
    'phpdoc_to_return_type' => ['types_map' => $typesMap],
    'phpdoc_to_property_type' => ['types_map' => $typesMap],
    'fully_qualified_strict_types' => ['import_symbols' => true],
    'php_unit_attributes' => false, // as is not yet supported by PhpCsFixerInternal/configurable_fixer_template
    'control_structure_braces' => false,
    'echo_tag_syntax' => ['format' => 'short'],
    'indentation_type' => true,
  ])
  ->setFinder(
    (new Finder())
      ->ignoreDotFiles(false)
      ->ignoreVCSIgnored(true)
      ->in(__DIR__)
  )
;
