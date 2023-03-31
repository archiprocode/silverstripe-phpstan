<?php

namespace Syntro\SilverstripePHPStan\Type;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\FunctionTypeSpecifyingExtension;
use PHPStan\Type\TypeCombinator;
use Syntro\SilverstripePHPStan\Utility;

class HasExtensionFunctionTypeSpecifier implements FunctionTypeSpecifyingExtension, TypeSpecifierAwareExtension
{

    /**
     * @var TypeSpecifier
     */
    private $typeSpecifier;

    /**
     * I can't figure out how to get the function name from the FuncCall node, so
     * @param FunctionReflection $functionReflection
     * @param FuncCall $node
     * @param TypeSpecifierContext $context
     * @return bool
     */
    public function isFunctionSupported(FunctionReflection $functionReflection, FuncCall $node, TypeSpecifierContext $context): bool
    {
        return preg_match('/has_extension$/', $functionReflection->getName())
            && $context->truthy()
            && count($node->getArgs()) === 2;
    }

    /**
     * @throws \Exception
     */
    public function specifyTypes(FunctionReflection $functionReflection, FuncCall $node, Scope $scope, TypeSpecifierContext $context): SpecifiedTypes
    {
        $currentType = $scope->getType($node->args[0]->value);
        $extension = Utility::getTypeFromVariable($node->args[1]->value, $functionReflection);
        return $this->typeSpecifier->create($node->args[0]->value, TypeCombinator::union($currentType, $extension), TypeSpecifierContext::createTruthy(), true);
    }

    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }
}
