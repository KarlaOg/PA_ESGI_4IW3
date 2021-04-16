<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Offer;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getBrandAndInfluencer()
    {
        // $conn = $this->getEntityManager()->getConnection();

        // $sql = '
        // SELECT * FROM user_account
        // LEFT JOIN brand ON user_account.id = brand.user_id_id
        // LEFT JOIN influencer ON user_account.id = influencer.user_id_id';
        // $stmt = $conn->prepare($sql);
        // $stmt->execute([]);


        // return $stmt->fetchAllAssociative();
        return $this->createQueryBuilder('u')
            // ->from(Brand::class, 'b')
            // ->leftJoin('u.brands', 'b.UserId')
            ->leftJoin(
                Brand::class,
                'b',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'b.UserId = u.id'
            )
            ->select('u')
            ->getQuery()
            ->getResult();
    }
}
