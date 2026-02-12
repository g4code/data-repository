<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\Php81\Rector\Class_\MyCLabsClassToEnumRector;
use Rector\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictBoolReturnExprRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->skip([
        MyCLabsMethodCallToEnumConstRector::class,
        MyCLabsClassToEnumRector::class
    ]);

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->rule(RemoveUselessParamTagRector::class);
    $rectorConfig->rule(ReturnTypeFromReturnNewRector::class);
    $rectorConfig->rule(ReturnTypeFromStrictBoolReturnExprRector::class);
    $rectorConfig->rule(ReturnTypeFromStrictNativeCallRector::class);
    $rectorConfig->rule(ReturnTypeFromStrictNewArrayRector::class);
    $rectorConfig->rule(ReturnTypeFromStrictTypedPropertyRector::class);

    // define sets of rules
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82,
    ]);
};
