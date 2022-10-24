<?php

namespace App\Warehouse\Repository;

use App\Warehouse\Entity\WarehouseDimension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarehouseDimension>
 *
 * @method WarehouseDimension|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseDimension|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseDimension[]    findAll()
 * @method WarehouseDimension[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseDimensionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseDimension::class);
    }

    public function add(WarehouseDimension $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarehouseDimension $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
