<?php

// @codingStandardsIgnoreStart
namespace DataHasExtensionMethodTypeSpecifierNamespace;

// SilverStripe
use SilverStripe\Core\Extensible;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ViewableData;
use function PHPStan\Testing\assertType;
use function PHPUnit\Framework\assertTrue;

class FooDataExtension extends DataExtension
{
    public function doFoo()
    {
        Foo::add_extension(\DataHasExtensionMethodTypeSpecifierNamespace\FooDataExtension::class);
        $foo = new Foo();
        if ($foo->hasExtension(\DataHasExtensionMethodTypeSpecifierNamespace\FooDataExtension::class)) {
            assertType(
                sprintf('%s|%s',
                    \DataHasExtensionMethodTypeSpecifierNamespace\Foo::class,
                    \DataHasExtensionMethodTypeSpecifierNamespace\FooDataExtension::class
                ),
                $foo
            );
        }
        die;
    }
}

class Foo extends ViewableData
{
}

// @codingStandardsIgnoreEnd

