<?php

namespace App\Warehouse\Repository;

use App\Warehouse\Entity\WarehouseItemHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarehouseItemHistory>
 *
 * @method WarehouseItemHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseItemHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseItemHistory[]    findAll()
 * @method WarehouseItemHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseItemHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseItemHistory::class);
    }

    public function add(WarehouseItemHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarehouseItemHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
