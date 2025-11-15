<?php

namespace App\Repository;

use App\Entity\SampleEntity;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SampleEntityRepository
{
    public function findById(int $id): ?SampleEntity;

    /**
     * @param string $name
     * @return SampleEntity[]
     */
    public function findByName(string $name): array;

    public function save(SampleEntity $sampleEntity): void;
}
