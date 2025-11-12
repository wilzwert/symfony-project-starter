<?php

namespace App\Tests\Sample\Repository;

use App\Repository\SampleEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        $found = $this->repository->findOneById(1);

        self::assertNotNull($found);
        self::assertSame('Sample test entity', $found->getName());
    }

}
