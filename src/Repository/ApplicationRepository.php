<?php

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Application|null find($id, $lockMode = null, $lockVersion = null)
 * @method Application|null findOneBy(array $criteria, array $orderBy = null)
 * @method Application[]    findAll()
 * @method Application[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    // /**
    //  * @return Application[] Returns an array of Application objects
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
    public function findOneBySomeField($value): ?Application
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findApplicationAndInfluencer($value): array
    {
        return $this->createQueryBuilder('application')
            ->select('a', 'i')
            ->from(
                'App\Entity\Application',
                'a'
            )
            ->leftJoin('a.influencerId', 'i')
            ->where('i = :influencerId')
            ->setParameter('influencerId', $value)
            ->getQuery()
            ->getResult()
            ;
    }
    public function influencerApplyOfferId($value, $offer): array
    {
        return $this->createQueryBuilder('application')
            ->select('a', 'i')
            ->from(
                'App\Entity\Application',
                'a',
            )
            ->leftJoin('a.influencerId', 'i')
            ->andWhere('a.offer = :offerId')
            ->andWhere('i = :influencerId')

            ->setParameters(['influencerId' => $value, 'offerId' => $offer])


            ->getQuery()
            ->getResult();
    }
}
