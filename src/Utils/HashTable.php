<?php

namespace App\Utils;

class HashTable
{
    public static function getHashTableFromArray(array $array, string $property): array
    {
        $hashTable = [];

        foreach ($array as $element) {
            $hashTable[$element[$property]] = $element;
        }

        return $hashTable;
    }

    public static function getHashTableFromEnum(array $array, string $property): array
    {
        $hashTable = [];

        foreach ($array as $element) {
            $hashTable[$element->$property] = $element;
        }

        return $hashTable;
    }
}