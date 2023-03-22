<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PHP82Migration' => true,
        '@PHP81Migration' => true,
        '@PHP80Migration' => true,
        '@PHP80Migration:risky' => true,
        '@PHP74Migration' => true,
        '@PHP74Migration:risky' => true,
        '@Symfony:risky' => true,
        'single_blank_line_before_namespace' => true,
        'single_blank_line_at_eof' => true,
        'align_multiline_comment' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_before_statement' => true,
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'compact_nullable_typehint' => true,
        'heredoc_to_nowdoc' => true,
        'list_syntax' => ['syntax' => 'long'],
        'no_null_property_initialization' => true,
        'no_superfluous_elseif' => true,
        'no_unneeded_curly_braces' => true,
        'no_unneeded_final_method' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_order' => true,
        'phpdoc_types_order' => true,
        'phpdoc_to_comment' => false,
        'phpdoc_to_return_type' => true,
        'semicolon_after_instruction' => true,
        'single_line_comment_style' => ['comment_types' => ['hash']],
        'strict_comparison' => true,
        'strict_param' => true,
        'yoda_style' => false,
        'native_function_invocation' => false,
        'php_unit_strict' => false,
        'php_unit_test_class_requires_covers' => true,
        'php_unit_namespaced' => ['target' => 'newest'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'php_unit_test_case_static_method_calls' => ['call_type' => 'this'],
        'void_return' => true,
        'global_namespace_import' => ['import_classes' => true],
        'date_time_immutable' => true,
    ])
    ->setFinder($finder)
;