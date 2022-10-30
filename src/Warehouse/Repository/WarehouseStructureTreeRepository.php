<?php

namespace App\Warehouse\Repository;

use App\Warehouse\Entity\WarehouseStructureTree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarehouseStructureTree>
 *
 * @method WarehouseStructureTree|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseStructureTree|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseStructureTree[]    findAll()
 * @method WarehouseStructureTree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseStructureTreeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseStructureTree::class);
    }

    public function add(WarehouseStructureTree $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(WarehouseStructureTree $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findWithoutParent(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.parent is null')
            ->getQuery()
            ->getResult();
    }
}
