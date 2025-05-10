<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface;
use Symfony\Component\Security\Http\Authentication\FormLoginAuthenticator;

class SecurityController extends AbstractController
{
    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): JsonResponse {
		try {
			$data = json_decode($request->getContent(), true);
			$user = new User();
			$user->setEmail($data['email']);
			$user->setRoles(['ROLE_USER']);
			$user->setIsApproved(false); // Par défaut, non validé
			$user->setIsLocked(false);

			$hashedPassword = $hasher->hashPassword($user, $data['password']);
			$user->setPassword($hashedPassword);

			$em->persist($user);
			$em->flush();

			return $this->json(['message' => 'Compte créé. En attente de validation par un administrateur.', 'status' => "ACCOUNT_CREATED_WAITING_ADMIN_VALIDATION"], 201);
		} catch (\Throwable $th) {
			return $this->json(['message' => 'Erreur lors de la création du compte.', 'error' => $th->getMessage()], 500);
		}
    }

}