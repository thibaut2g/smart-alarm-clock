<?php

namespace App\Repository;

use App\Entity\GoogleConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GoogleConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoogleConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoogleConfig[]    findAll()
 * @method GoogleConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoogleConfigRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoogleConfig::class);
    }

    // /**
    //  * @return GoogleConfig[] Returns an array of GoogleConfig objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GoogleConfig
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
