<?php

namespace App\Serializer\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;

class DateTimeDenormalizer implements DenormalizerInterface
{
    private DenormalizerInterface $decorated;

    public function __construct(DenormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return $type === \DateTimeInterface::class;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        if (is_string($data)) {
            try {
                return new \DateTime($data);
            } catch (\Exception $e) {
                throw new NotNormalizableValueException(sprintf('The date "%s" is not a valid date format.', $data));
            }
        }

        return $this->decorated->denormalize($data, $type, $format, $context);
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            \DateTimeInterface::class => true,
        ];
    }
}