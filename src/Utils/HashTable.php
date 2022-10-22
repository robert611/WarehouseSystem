<?php

namespace App\Utils;

class HashTable
{
    /**
     * @param array<mixed> $array
     * @param string $property
     * @return array<mixed>
     */
    public static function getHashTableFromArray(array $array, string $property): array
    {
        $hashTable = [];

        foreach ($array as $element) {
            $hashTable[$element[$property]] = $element;
        }

        return $hashTable;
    }

    /**
     * @param array<mixed> $array
     * @param string $property
     * @return array<mixed>
     */
    public static function getHashTableFromEnum(array $array, string $property): array
    {
        $hashTable = [];

        foreach ($array as $element) {
            $hashTable[$element->$property] = $element;
        }

        return $hashTable;
    }
}