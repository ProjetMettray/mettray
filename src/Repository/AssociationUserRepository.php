<?php

namespace App\Repository;

use App\Entity\AssociationUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AssociationUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssociationUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssociationUser[]    findAll()
 * @method AssociationUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssociationUser::class);
    }

    // /**
    //  * @return AssociationUser[] Returns an array of AssociationUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssociationUser
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
