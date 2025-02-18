<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\AuthDto;
use App\Service\AuthService;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Attributes as OA;

#[AsController]
final class AuthController
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator
    ) {
    }

    #[Route('/login', name: 'api_login', methods: ['POST'])]
    #[OA\Post(
        description: "Logs the user in and returns a JWT token.",
        summary: "Log in",
        security: [],
        requestBody: new OA\RequestBody(
            required: true,
            content: new Model(type: AuthDto::class)
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "User logged in successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string", example: "someToken")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Invalid credentials",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "Invalid credentials")
                    ]
                )
            )
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        $dto = $this->serializer->deserialize($request->getContent(), AuthDto::class, 'json');
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_BAD_REQUEST);
        }

        $token = $this->authService->getJWT($dto);

        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }
}
