<?php


namespace App\Repository;


use App\Entity\Brand;

use App\Entity\Offer;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**

 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)

 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)

 * @method Offer[]    findAll()

 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)

 */

class OfferRepository extends ServiceEntityRepository

{

    public function __construct(ManagerRegistry $registry)

    {

        parent::__construct($registry, Offer::class);
    }
}
