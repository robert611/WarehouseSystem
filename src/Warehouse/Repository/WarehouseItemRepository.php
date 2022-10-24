<?php

namespace App\Warehouse\Repository;

use App\Warehouse\Entity\WarehouseItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarehouseItem>
 *
 * @method WarehouseItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseItem[]    findAll()
 * @method WarehouseItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseItem::class);
    }

    public function add(WarehouseItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarehouseItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
