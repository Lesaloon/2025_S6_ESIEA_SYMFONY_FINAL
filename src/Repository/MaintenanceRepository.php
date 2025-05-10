<?php

namespace App\Repository;

use App\Entity\Maintenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Maintenance>
 */
class MaintenanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Maintenance::class);
    }

    /** Récupère toutes les maintenances d'un équipement */
	public function findAllByGear($gear): array
	{
		return $this->createQueryBuilder('m')
			->where('m.gear = :gear')
			->setParameter('gear', $gear)
			->orderBy('m.date', 'ASC')
			->getQuery()
			->getResult();
	}

	/** Supprime une maintenance */
	public function removeMaintenance($maintenance): void
	{
		$this->getEntityManager()->remove($maintenance);
		$this->getEntityManager()->flush();
	}

	/** Persiste une nouvelle maintenance */
	public function saveMaintenance($maintenance): void
	{
		$this->getEntityManager()->persist($maintenance);
		$this->getEntityManager()->flush();
	}
	/** Récupère une maintenance par son ID */
	public function findMaintenanceById($id): ?Maintenance
	{
		return $this->createQueryBuilder('m')
			->where('m.id = :id')
			->setParameter('id', $id)
			->getQuery()
			->getOneOrNullResult();
	}
	/** Récupère toutes les maintenances */
	public function findAll(): array
	{
		return $this->createQueryBuilder('m')
			->orderBy('m.date', 'ASC')
			->getQuery()
			->getResult();
	}
	/** Récupère toutes les maintenances d'un utilisateur */
	public function findAllByUser($user): array
	{
		return $this->createQueryBuilder('m')
			->join('m.gear', 'g')
			->where('g.user = :user')
			->setParameter('user', $user)
			->orderBy('m.date', 'ASC')
			->getQuery()
			->getResult();
	}

}