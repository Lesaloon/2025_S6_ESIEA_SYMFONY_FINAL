<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/unapproved-users', name: 'admin_unapproved_users', methods: ['GET'])]
    public function listUnapproved(EntityManagerInterface $em): JsonResponse
    {
        $users = $em->getRepository(User::class)->findBy(['isApproved' => false]);

        return $this->json([
            'unapproved_users' => array_map(fn($user) => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'isLocked' => $user->isLocked(),
            ], $users)
        ]);
    }

    #[Route('/approve/{id}', name: 'admin_approve_user', methods: ['POST'])]
    public function approveUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        if ($user->isApproved()) {
            return $this->json(['message' => 'User already approved.'], 400);
        }

        $user->setIsApproved(true);
        $user->setIsLocked(false); // Optional: unlock on approval
        $em->flush();

        return $this->json(['message' => 'User approved successfully.']);
    }
}
