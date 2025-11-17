<?php

namespace App\Dto;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class CreateSampleEntityRequest
{
    public function __construct(
        private string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
