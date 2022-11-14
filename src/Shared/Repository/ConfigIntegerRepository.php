<?php

namespace App\Shared\Repository;

use App\Shared\Entity\ConfigInteger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConfigInteger>
 *
 * @method ConfigInteger|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfigInteger|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfigInteger[]    findAll()
 * @method ConfigInteger[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigIntegerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfigInteger::class);
    }

    public function add(ConfigInteger $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ConfigInteger $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
