<?php

namespace App\Repository;

use App\Entity\UserPreferences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserPreferences|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPreferences|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPreferences[]    findAll()
 * @method UserPreferences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPreferencesRepository extends ServiceEntityRepository
{
    /**
     * UserPreferencesRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserPreferences::class);
    }
}
