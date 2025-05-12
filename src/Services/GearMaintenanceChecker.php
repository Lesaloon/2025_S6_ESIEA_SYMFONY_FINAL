<?php

namespace App\Services;

use App\Entity\Gear;
use App\Repository\GearRepository;
use DateTimeImmutable;

class GearMaintenanceChecker
{
    public function __construct(private GearRepository $gearRepo) {}

    /**
     * Retourne les équipements dont la dernière maintenance remonte à plus de $days jours
     */
    public function getGearsNeedingMaintenanceForUser(object $user, int $days): array
    {
        $limitDate = new DateTimeImmutable("-$days days");
        $gears = $this->gearRepo->findAllByUser($user);

        $needingMaintenance = [];

        foreach ($gears as $gear) {
            $lastMaintenance = $gear->getMaintenances()->last();

            if (!$lastMaintenance || $lastMaintenance->getDate() < $limitDate) {
                $needingMaintenance[] = $gear;
            }
        }

        return $needingMaintenance;
    }
}
