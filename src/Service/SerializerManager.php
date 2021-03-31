<?php declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerManager
{
    /**
     * @var array
     */
    private array $encoders;

    /**
     * @var array
     */
    private array $normalizers;

    /**
     * @var Serializer
     */
    private Serializer $serializer;

    public function __construct()
    {
        $this->encoders = [new XmlEncoder(), new JsonEncoder()];
        $this->normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($this->normalizers, $this->encoders);
    }

    /**
     * @param $content
     * @param string $type
     * @return object
     */
    public function deserializer($content, string $type): object
    {
        return $this->serializer->deserialize($content, $type, 'json');
    }
}