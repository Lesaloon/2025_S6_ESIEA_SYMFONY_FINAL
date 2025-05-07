<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BaseController extends AbstractController
{
    #[Route('/public', name: 'public_route', methods: ['GET'])]
    public function public(): JsonResponse
    {
        return $this->json(['message' => 'Bienvenue sur la route publique !']);
    }

    #[Route('/user', name: 'user_route', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function user(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'message' => 'Bienvenue utilisateur authentifié !',
            'email' => $user->getUserIdentifier()
        ]);
    }

    #[Route('/admin', name: 'admin_route', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(): JsonResponse
    {
        return $this->json(['message' => 'Bienvenue sur la route ADMIN !']);
    }
}
