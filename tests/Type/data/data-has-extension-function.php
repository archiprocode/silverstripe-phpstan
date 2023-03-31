<?php

use SilverStripe\Core\Extension;
use SilverStripe\ORM\DataExtension;

/**
 * @param mixed $class
 * @param class-string<DataExtension|Extension> $extension
 * @return mixed
 */
function has_extension(mixed $class, string $extension)
{
    return $class::hasExtension($extension);
}
