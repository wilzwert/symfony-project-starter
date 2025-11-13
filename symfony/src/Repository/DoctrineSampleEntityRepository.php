<?php

namespace App\Repository;

use App\Entity\SampleEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SampleEntity>
 */
class DoctrineSampleEntityRepository extends ServiceEntityRepository implements SampleEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, SampleEntity::class);
    }

    public function findById(int $id): ?SampleEntity
    {
        return parent::findOneById($id);
    }


    /**
     * @param string $name
     * @return SampleEntity[]
     */
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.name LIKE :name')
            ->setParameter('name', '%' . mb_strtolower($name) . '%')
            ->getQuery()
            ->getResult();
    }

    public function save(SampleEntity $sampleEntity): void
    {
        $this->entityManager->persist($sampleEntity);
    }
}
