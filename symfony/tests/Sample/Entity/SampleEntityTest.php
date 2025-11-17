<?php

namespace App\Tests\Sample\Entity;

use App\Entity\SampleEntity;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class SampleEntityTest extends TestCase
{
    #[Test]
    public function shouldExposeProvidedProperties(): void
    {
        $entity = new SampleEntity(2, 'name');
        self::assertEquals(2, $entity->getId());
    }
}
