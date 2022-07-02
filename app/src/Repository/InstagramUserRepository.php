<?php

namespace App\Repository;

use App\Entity\InstagramUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstagramUser>
 *
 * @method InstagramUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstagramUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstagramUser[]    findAll()
 * @method InstagramUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstagramUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstagramUser::class);
    }

    public function add(InstagramUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InstagramUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @param string $field
     * @param string $param
     * @param string $operator
     * @return int|mixed|string
     */
    public function findOne(
        string $field,
        string $param,
        string $operator
    )
    {
        return $this->createQueryBuilder('e')
            ->where('e.' . $field . ' ' . $operator . ' :' . $field)
            ->setParameter($field, $param)
            ->setMaxResults(1)
            ->orderBy('e.id', 'DESC')
            ->getQuery()
            ->useQueryCache(false)
            ->useResultCache(false)
            ->getResult();
    }


    /**
     * @param InstagramUser|null $instagramUser
     * @return InstagramUser|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneOtherThan(?InstagramUser $instagramUser): ?InstagramUser
    {
        $qb = $this->createQueryBuilder('instagram_user')
            ->setMaxResults(1);

        if ($instagramUser && $instagramUser->getId()) {
            $qb->andWhere('instagram_user.id != :id')
                ->setParameter('id', $instagramUser->getId());
        }

        return $qb->getQuery()->getOneOrNullResult();
    }


//    /**
//     * @return InstagramUser[] Returns an array of InstagramUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InstagramUser
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
