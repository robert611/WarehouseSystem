<?php

namespace App\Shared\Enum;

interface TranslatableToString
{
    public static function translateCaseToString(mixed $case): string;
}