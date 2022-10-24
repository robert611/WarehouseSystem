<?php

namespace App\Warehouse\Repository;

use App\Warehouse\Entity\WarehouseLeafSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarehouseLeafSettings>
 *
 * @method WarehouseLeafSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseLeafSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseLeafSettings[]    findAll()
 * @method WarehouseLeafSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseLeafSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseLeafSettings::class);
    }

    public function add(WarehouseLeafSettings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarehouseLeafSettings $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
