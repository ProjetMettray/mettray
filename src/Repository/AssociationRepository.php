<?php

namespace App\Repository;

use App\Entity\Association;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Association|null find($id, $lockMode = null, $lockVersion = null)
 * @method Association|null findOneBy(array $criteria, array $orderBy = null)
 * @method Association[]    findAll()
 * @method Association[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Association::class);
    }

    public function queryOwnedBy($userId, $roomId) {

        $query = $this  ->createQueryBuilder('a')      
                        ->innerJoin('a.users', 'u')
                        ->innerJoin('a.rooms', 'r')
                        ->andWhere('u.id = :userId')
                        ->andWhere('r.id = :roomId')
                        ->setParameter('userId', $userId)
                        ->setParameter('roomId', $roomId);
    
        return $query;
    }

    public function queryAssociations() {

        $query = $this  ->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
    
        return $query;
    }

    public function findOwnedBy($user) {
        return $this->queryOwnedBy($user)
                ->getQuery()
                ->getResult();
    }


    /**
    /* @return Association[] Returns an array of Association objects
    */

    public function findByUserId($userId)
    {
        return $this->createQueryBuilder('a')
            ->setParameter('userId', $userId)
            ->innerJoin('a.users', 'u')
            ->andWhere('u.id = :userId')
            ->orderBy('a.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Association
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
 