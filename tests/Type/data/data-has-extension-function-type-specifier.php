<?php


// @codingStandardsIgnoreStart
namespace DataHasExtensionFunctionTypeSpecifierNamespace;

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
        Foo::add_extension(FooDataExtension::class);
        $foo = new Foo();
        if (has_extension($foo, FooDataExtension::class)) {
            echo 'yo up';
            assertType(
                sprintf('%s|%s',
                    Foo::class,
                    FooDataExtension::class
                ),
                $foo
            );
        } else {
            echo 'what up';
        }
        die;
    }
}

class Foo extends ViewableData
{
}

// @codingStandardsIgnoreEnd

