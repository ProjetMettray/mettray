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

    public function queryOwnedBy($userId) {

        $query = $this  ->createQueryBuilder('a')      
                        ->innerJoin('a.associationUsers', 'au')
                        ->andWhere('au.user = :id')                
                        ->setParameter('id', $userId);
    
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


    // /**
    //  * @return Association[] Returns an array of Association objects
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
 