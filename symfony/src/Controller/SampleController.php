<?php

namespace App\Controller;

use App\Dto\CreateSampleEntityRequest;
use App\Dto\SampleEntityResponse;
use App\Service\SampleEntityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Wilhelm Zwertvaegher
 */
#[Route('/api/samples')]
final class SampleController extends AbstractController
{
    public function __construct(
        private readonly SampleEntityService $sampleEntityService
    ) {
    }

    #[Route('', methods: ['GET', 'HEAD'])]
    public function search(Request $request): JsonResponse
    {
        if (!$request->query->has('q')) {
            throw new BadRequestHttpException();
        }

        return $this->json(array_map(
            fn ($item) => new SampleEntityResponse($item),
            $this->sampleEntityService->search($request->query->get('q'))
        ));
    }

    #[Route('', methods: ['POST'])]
    public function create(#[MapRequestPayload] CreateSampleEntityRequest $createSampleEntityRequest): JsonResponse
    {
        $created = $this->sampleEntityService->create($createSampleEntityRequest->getName());
        return $this->json(
            new SampleEntityResponse($created),
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl('api_samples_detail', ['id' => $created->getId()])]
        );
    }

    #[Route('/{id}', name: 'api_samples_detail', methods: ['GET'])]
    public function get(int $id): JsonResponse
    {
        return $this->json(new SampleEntityResponse($this->sampleEntityService->getById($id)));
    }
}
