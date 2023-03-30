<?php

namespace Syntro\SilverstripePHPStan\Type;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\MethodTypeSpecifyingExtension;
use PHPStan\Type\ThisType;
use PHPStan\Type\TypeCombinator;
use PHPUnit\Util\Type;
use SilverStripe\Core\Extensible;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ViewableData;
use Syntro\SilverstripePHPStan\Utility;

class HasExtensionMethodTypeSpecifier implements MethodTypeSpecifyingExtension, TypeSpecifierAwareExtension
{
    /**
     * @var TypeSpecifier
     */
    private  $typeSpecifier;

    public function getClass(): string
    {
        return ViewableData::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection, MethodCall $node, TypeSpecifierContext $context): bool
    {
        return $methodReflection->getName() === 'hasExtension'
            && $context->true()
            && isset($node->getArgs()[0]);
    }

    /**
     * @throws \Exception
     */
    public function specifyTypes(MethodReflection $methodReflection, MethodCall $node, Scope $scope, TypeSpecifierContext $context): SpecifiedTypes
    {
        $expr = $node->getArgs()[0]->value;
        $extension = Utility::getTypeFromVariable($expr, $methodReflection);
        $type = TypeCombinator::intersect(new ThisType($methodReflection->getDeclaringClass()),$extension);
        return $this->typeSpecifier->create($expr, $type, TypeSpecifierContext::createTrue());
    }

    /**
     * @param TypeSpecifier $typeSpecifiera
     * @return void
     */
    public function setTypeSpecifier(TypeSpecifier $typeSpecifier) : void
    {
        $this->typeSpecifier = $typeSpecifier;
    }
}
