<?php

namespace App\Tests\Sample\Controller;

use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Wilhelm Zwertvaegher
 */
class SampleEntityControllerIT extends WebTestCase
{
    #[Test]
    public function shouldCreateSampleEntity(): void
    {
        $client = self::createClient();

        $client->jsonRequest('POST', '/api/samples', [
            'name' => 'Created'
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHasHeader('Location', '/api/samples/2');

        $response = $client->getResponse();
        $data = json_decode($response->getContent());
        self::assertEquals('created', $data->name);
        self::assertEquals(2, $data->id);
    }

    #[Test]
    public function shouldGetNoEntity(): void
    {
        $client = self::createClient();

        $client->jsonRequest('GET', '/api/samples?q=nonexistent');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $client->getResponse();
        self::assertEquals('[]', $response->getContent());
    }

    #[Test]
    public function shouldGetOneEntity(): void
    {
        $client = self::createClient();

        $client->jsonRequest('GET', '/api/samples?q=test');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $client->getResponse();
        $data = json_decode($response->getContent());
        self::assertIsArray($data);
        self::assertCount(1, $data);
        self::assertEquals(1, $data[0]->id);
        self::assertEquals('sample test entity', $data[0]->name);
    }

    #[Test]
    public function shouldGetEntityDetail(): void
    {
        $client = self::createClient();

        $client->jsonRequest('GET', '/api/samples/1');

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $client->getResponse();
        $data = json_decode($response->getContent());
        self::assertIsObject($data);
        self::assertEquals(1, $data->id);
        self::assertEquals('sample test entity', $data->name);
    }
}
