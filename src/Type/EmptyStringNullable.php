<?php

namespace Hyperion\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class EmptyStringNullable extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return 'VARCHAR(255)';
    }

    public function getName()
    {
        return 'strnullable';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value !== '' ? $value : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
       return (string) $value;
    }
}