<?php

namespace App\Repository;

use App\Entity\RaspbianHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RaspbianHelper|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaspbianHelper|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaspbianHelper[]    findAll()
 * @method RaspbianHelper[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaspbianHelperRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaspbianHelper::class);
    }

    // /**
    //  * @return RaspbianHelper[] Returns an array of RaspbianHelper objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RaspbianHelper
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
