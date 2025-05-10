<?php

namespace App\Repository;

use App\Entity\Gear;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gear>
 */
class GearRepository extends ServiceEntityRepository
{
	private EntityManagerInterface $em;
    public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Gear::class);
		$this->em = $registry->getManager(); // ✅ on récupère l'EntityManager manuellement
	}

    /**
     * Récupère tous les équipements d’un utilisateur donné.
     */
    public function findAllByUser(User $user): array
    {
        return $this->createQueryBuilder('g')
            ->where('g.user = :user')
            ->setParameter('user', $user)
            ->orderBy('g.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Supprime un équipement si l'utilisateur en est le propriétaire.
     */
    public function removeGearIfOwner(Gear $gear, User $user): bool
    {
        if ($gear->getUser() !== $user) {
            return false;
        }

        $this->em->remove($gear);
        $this->em->flush();
        return true;
    }

    /**
     * Persiste un nouvel équipement pour un utilisateur.
     */
    public function saveGear(Gear $gear, User $user): void
    {
        $gear->setUser($user);
        $this->em->persist($gear);
        $this->em->flush();
    }
}
