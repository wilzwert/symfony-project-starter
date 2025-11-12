<?php

namespace App\Dto;

use App\Entity\SampleEntity;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class SampleEntityResponse
{
    private int $id;

    private string $name;

    public function __construct(
        SampleEntity $sampleEntity
    ) {
        $this->id = $sampleEntity->getId();
        $this->name = $sampleEntity->getName();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
