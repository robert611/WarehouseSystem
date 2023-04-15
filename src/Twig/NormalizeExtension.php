<?php

namespace App\Twig;

use Symfony\Component\Serializer\SerializerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class NormalizeExtension extends AbstractExtension
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('normalize', [$this, 'normalize']),
        ];
    }

    public function normalize($data, string $format = 'json', array $context = []): string|array
    {
        if (is_array($data)) {
            $serialized = [];

            foreach ($data as $element) {
                $serialized[] = $this->serializer->normalize($element, $format, $context);
            }

            return $serialized;
        }

        return $this->serializer->normalize($data, $format, $context);
    }
}
