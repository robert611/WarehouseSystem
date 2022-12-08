<?php

namespace App\Product\Serializer;

use App\Product\Model\Enum\SaleTypeEnum;
use DateTimeInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

class SearchEngineSerializer
{
    public static function normalizeSearchEngineResults(array $results): array
    {
        $dateCallback = function ($innerObject) {
            return $innerObject instanceof DateTimeInterface ? $innerObject->format('Y-m-d H:i:s') : '';
        };
        $saleTypeCallback = function ($innerObject) {
            $name = (SaleTypeEnum::translateCaseToString($innerObject));
            return ['type' => $innerObject, 'name' => $name];
        };
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'saleType' => $saleTypeCallback,
            ],
        ];

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory, defaultContext: $defaultContext)];

        $serializer = new Serializer($normalizers, $encoders);

        try {
            return $serializer->normalize($results, null, ['groups' => 'search_engine']);
        } catch (ExceptionInterface) {
            return [];
        }
    }
}