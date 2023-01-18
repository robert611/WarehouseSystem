<?php

namespace App\Allegro\Repository;

use App\Allegro\Entity\AllegroAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AllegroAccount>
 *
 * @method AllegroAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method AllegroAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method AllegroAccount[]    findAll()
 * @method AllegroAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AllegroAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AllegroAccount::class);
    }

    public function add(AllegroAccount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AllegroAccount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AllegroAccount[] Returns an array of AllegroAccount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AllegroAccount
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
