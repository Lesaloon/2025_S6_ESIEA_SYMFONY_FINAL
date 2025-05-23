<?php
namespace App\Controller;

use App\Entity\Gear;
use App\Entity\Maintenance;
use App\Repository\GearRepository;
use App\Repository\MaintenanceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/gear')]
#[IsGranted('ROLE_USER')]
class GearController extends AbstractController
{
    #[Route('', name: 'gear_list', methods: ['GET'])]
    public function list(GearRepository $repo): JsonResponse
    {
        $gears = $repo->findAllByUser($this->getUser());
        return $this->json($gears, 200, [], ['groups' => 'gear:read', 'gear:write']);
    }

    #[Route('', name: 'gear_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        GearRepository $repo
    ): JsonResponse {
        $gear = $serializer->deserialize($request->getContent(), Gear::class, 'json');

        $errors = $validator->validate($gear);
        if (count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $repo->saveGear($gear, $this->getUser());

        return $this->json($gear, 201, [], ['groups' => 'gear:read']);
    }

	#[Route('/{id}', name: 'gear_show', methods: ['GET'])]
	public function show(Gear $gear): JsonResponse
	{
		if ($gear->getUser() !== $this->getUser()) {
			return $this->json(['message' => 'Unauthorized'], 403);
		}
		return $this->json($gear, 200, [],['groups' => 'gear:read']);
	}

	#[Route('/{id}', name: 'gear_edit', methods: ['PUT'])]
	public function edit(
		Request $request,
		SerializerInterface $serializer,
		ValidatorInterface $validator,
		Gear $gear,
		GearRepository $repo
	): JsonResponse {
		$updatedGear = $serializer->deserialize($request->getContent(), Gear::class, 'json', ['object_to_populate' => $gear]);
		$errors = $validator->validate($updatedGear);
		if (count($errors) > 0) {
			return $this->json($errors, 400);
		}
		$updatedGear->setUser($this->getUser());
		$repo->saveGear($updatedGear, $this->getUser());
		return $this->json($updatedGear, 200, []);
	}

    #[Route('/{id}', name: 'gear_delete', methods: ['DELETE'])]
    public function delete(Gear $gear, GearRepository $repo): JsonResponse
    {
        $deleted = $repo->removeGearIfOwner($gear, $this->getUser());

        if (!$deleted) {
            return $this->json(['message' => 'Unauthorized'], 403);
        }

        return $this->json(['message' => 'Gear deleted']);
    }

	#[Route('/{id}/maintenance', name: 'gear_maintenance_list', methods: ['GET'])]
	public function maintenanceList(Gear $gear): JsonResponse
	{
		if ($gear->getUser() !== $this->getUser()) {
			return $this->json(['message' => 'Unauthorized'], 403);
		}
		return $this->json($gear->getMaintenances(), 200, ['groups' => 'maintenance:read']);
	}

	#[Route('/{id}/maintenance', name: 'gear_maintenance_create', methods: ['POST'])]
	public function maintenanceCreate(
		Request $request,
		SerializerInterface $serializer,
		ValidatorInterface $validator,
		MaintenanceRepository $maintenanceRepo,
		Gear $gear
	): JsonResponse {
		if ($gear->getUser() !== $this->getUser()) {
			return $this->json(['message' => 'Unauthorized'], 403);
		}
		$maintenance = $serializer->deserialize($request->getContent(), Maintenance::class, 'json');
		$errors = $validator->validate($maintenance);
		if (count($errors) > 0) {
			return $this->json($errors, 400);
		}
		$maintenance->setGear($gear);
		$maintenanceRepo->saveMaintenance($maintenance, true);
		return $this->json($maintenance, 201, [], ['groups' => 'maintenance:read']);
	}
}
