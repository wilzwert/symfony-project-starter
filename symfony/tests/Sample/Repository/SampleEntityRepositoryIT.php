<?php

namespace App\Tests\Sample\Repository;

use App\Repository\SampleEntityRepository;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class SampleEntityRepositoryIT extends KernelTestCase
{
    private SampleEntityRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->repository = $container->get(SampleEntityRepository::class);
    }

    #[Test]
    public function shouldFindById(): void
    {
        $found = $this->repository->findById(1);

        self::assertNotNull($found);
        self::assertSame('sample test entity', $found->getName());
    }

    #[Test]
    public function shouldFindByName(): void
    {
        $found = $this->repository->findByName('Sample');

        self::assertCount(1, $found);
        self::assertSame('sample test entity', $found[0]->getName());
        self::assertSame(1, $found[0]->getId());
    }
}
