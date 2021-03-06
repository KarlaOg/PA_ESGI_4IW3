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
}
