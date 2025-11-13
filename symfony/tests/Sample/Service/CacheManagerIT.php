<?php

namespace App\Tests\Sample\Service;

use App\Repository\SampleEntityRepository;
use App\Service\CacheManager;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class CacheManagerIT extends KernelTestCase
{

    private CacheManager $cacheManager;

    private SampleEntityRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->cacheManager = $container->get(CacheManager::class);
        $this->repository = $container->get(SampleEntityRepository::class);
    }

    #[Test]
    public function shouldGetSampleEntitiesFromRepositoryThenFromCache(): void
    {
        $key = 'search_entities_test';
        $entities = $this->cacheManager->get($key, fn () => $this->repository->findByName('test'));

        self::assertCount(1, $entities);
        $entitiesFromCache = $this->cacheManager->get($key, fn () => self::fail('Should get entities from cache'));

        self::assertCount(1, $entitiesFromCache);
    }

}
