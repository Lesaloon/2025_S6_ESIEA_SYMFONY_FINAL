<?php
namespace App\Controller;

use App\Services\GearMaintenanceChecker;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/gear')]
#[IsGranted('ROLE_USER')]
class GearMaintenanceController extends AbstractController
{
    #[Route('/needs-maintenance/{days}', name: 'gear_needs_maintenance', methods: ['GET'])]
    public function needsMaintenance(GearMaintenanceChecker $checker, int $days): JsonResponse
    {
        $user = $this->getUser();
        $gears = $checker->getGearsNeedingMaintenanceForUser($user, $days);

        return $this->json($gears, 200, [], ['groups' => 'gear:read']);
    }
}
