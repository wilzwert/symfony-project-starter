<?php

namespace App\Service;

use App\Entity\SampleEntity;
use App\Repository\SampleEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

/**
 * @author Wilhelm Zwertvaegher
 */
#[Autoconfigure]
readonly class DoctrineRedisSampleEntityService implements SampleEntityService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DefaultCacheManager $cacheManager,
        private SampleEntityRepository $repository,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function search(string $q): array
    {
        return $this->cacheManager->get('search_sample_entity_'.$q, fn () => $this->repository->findByName($q));
    }

    public function getById(int $id): ?SampleEntity
    {
        return $this->repository->findById($id);
    }

    public function create(string $name): SampleEntity
    {
        $entityToCreate = new SampleEntity(null, $name);
        $this->repository->save($entityToCreate);
        $this->entityManager->flush();

        return $entityToCreate;
    }
}
