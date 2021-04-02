<?php

namespace App\Repository;

use App\Entity\Influencer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**^
 * @method Influencer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Influencer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Influencer[]    findAll()
 * @method Influencer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InfluencerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Influencer::class);
    }


    // public function findAll()
    // {
    //     return $this->createQueryBuilder('i')
    //         ->select('i.id')
    //         ->orderBy('i.id', 'ASC')
    //         ->getQuery()
    //         ->getResult();
    // }

    public function findAllWithNames()
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('App:User', 'u', 'WITH', 'i.user = u.id')
            ->select('DISTINCT u.firstname, u.lastname')
            ->orderBy('u.lastname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findBySearch()
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('App:User', 'u', 'WITH', 'i.user = u.id')
            ->select('DISTINCT u.firstname, u.lastname')
            ->orderBy('u.lastname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function index(OfferRepository $offerRepository): Response

    {
        return $this->render('offer/index.html.twig', [

            'offers' => $offerRepository->findBy(array(), array('dateStart' => 'ASC'))

        ]);
    }

    // /**
    //  * @return Influencer[] Returns an array of Influencer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Influencer
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
