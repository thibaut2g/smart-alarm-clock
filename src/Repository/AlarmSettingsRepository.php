<?php

namespace App\Repository;

use App\Entity\AlarmSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AlarmSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlarmSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlarmSettings[]    findAll()
 * @method AlarmSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlarmSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlarmSettings::class);
    }

    // /**
    //  * @return AlarmSettings[] Returns an array of AlarmSettings objects
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
    public function findOneBySomeField($value): ?AlarmSettings
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
