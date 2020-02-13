<?php

namespace App\Repository;

use App\Entity\UGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method UGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UGroup[]    findAll()
 * @method UGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UGroup::class);
    }
}
