<?php

namespace App\Service;

use App\Entity\SampleEntity;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SampleEntityService
{
    /**
     * @param string $q
     * @return SampleEntity[]
     */
    public function search(string $q): array;

    /**
     * @param int $id
     * @return SampleEntity|null
     */
    public function getById(int $id): ?SampleEntity;

    /**
     * @param string $name
     * @return SampleEntity
     */
    public function create(string $name): SampleEntity;
}
