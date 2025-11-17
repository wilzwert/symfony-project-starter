<?php

namespace App\Service;

use App\Entity\SampleEntity;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SampleEntityService
{
    /**
     * @return SampleEntity[]
     */
    public function search(string $q): array;

    public function getById(int $id): ?SampleEntity;

    public function create(string $name): SampleEntity;
}
