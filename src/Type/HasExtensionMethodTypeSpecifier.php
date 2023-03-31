<?php

namespace Syntro\SilverstripePHPStan\Type;

use Exception;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\MethodTypeSpecifyingExtension;
use PHPStan\Type\TypeCombinator;
use SilverStripe\View\ViewableData;
use Syntro\SilverstripePHPStan\Utility;


/**
 * Class HasExtensionMethodTypeSpecifier
 * @package Syntro\SilverstripePHPStan\Type
 *
 * This type specifier allows for the following:
 * $tree = new SiteTree();
 * if ($tree->hasExtension(Versioned::class)) {
 *  // $tree is now SiteTree|Versioned
 * }
 *
 * Note that this only works if your class extends ViewableData. It's not
 * possible to do this for any class that uses Extensible.
 *
 */
class HasExtensionMethodTypeSpecifier implements MethodTypeSpecifyingExtension, TypeSpecifierAwareExtension
{
    /**
     * @var TypeSpecifier
     */
    private $typeSpecifier;

    public function getClass(): string
    {
        return ViewableData::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection, MethodCall $node, TypeSpecifierContext $context): bool
    {
        return $methodReflection->getName() === 'hasExtension'
            && $context->truthy()
            && isset($node->getArgs()[0]);
    }

    /**
     * @throws Exception
     */
    public function specifyTypes(MethodReflection $methodReflection, MethodCall $node, Scope $scope, TypeSpecifierContext $context): SpecifiedTypes
    {
        $expr = $node->getArgs()[0]->value;
        $extension = Utility::getTypeFromVariable($expr, $methodReflection);
        $type = TypeCombinator::union($scope->getType($node->var), $extension);
        return $this->typeSpecifier->create($node->var, $type, TypeSpecifierContext::createTrue(),true);
    }

    /**
     * @param TypeSpecifier $typeSpecifier
     * @return void
     */
    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }
}
