<?php

namespace App\Warehouse\Repository;

use App\Pagination\InterfaceRK\PaginationRepositoryInterface;
use App\Pagination\Model\Pagination;
use App\Warehouse\Entity\WarehouseItem;
use App\Warehouse\Model\Enum\WarehouseItemStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WarehouseItem>
 *
 * @method WarehouseItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method WarehouseItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method WarehouseItem[]    findAll()
 * @method WarehouseItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarehouseItemRepository extends ServiceEntityRepository implements PaginationRepositoryInterface
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

    public function getNotFreeLeafItemsCount(int $leafId): int
    {
        return $this->createQueryBuilder('item')
            ->select('count(item) as count')
            ->where('item.node = :leafId')
            ->andWhere('item.status != :status')
            ->setParameter('leafId', $leafId)
            ->setParameter('status', (WarehouseItemStatusEnum::FREE)->toString())
            ->getQuery()
            ->getResult()[0]['count'];
    }

    public function getLastNotFreeItemPosition(int $nodeId): ?int
    {
        try {
            $result = $this->createQueryBuilder('item')
                ->select('item.position')
                ->where('item.node = :nodeId')
                ->andWhere('item.status != :status')
                ->setParameter('nodeId', $nodeId)
                ->setParameter('status', (WarehouseItemStatusEnum::FREE)->toString())
                ->getQuery()
                ->getSingleScalarResult();
        } catch (NoResultException | NonUniqueResultException) {
            return null;
        }

        return $result;
    }

    public function getStatusCount(): array
    {
        return $this->createQueryBuilder('item')
            ->select('item.status, count(item) as count')
            ->groupBy('item.status')
            ->getQuery()
            ->getResult();
    }

    public function getPaginationResults(array $formData, Pagination $pagination): array
    {
        return $this->createQueryBuilder('item')
            ->setFirstResult($pagination->getOffset())
            ->setMaxResults($pagination->getResultsPerPage())
            ->where('item.node = :nodeId')
            ->setParameter('nodeId', $formData['nodeId'])
            ->getQuery()
            ->getResult();
    }

    public function getPaginationResultsCount(array $formData): int
    {
        return $this->createQueryBuilder('item')
            ->select('count(item) as count')
            ->where('item.node = :nodeId')
            ->setParameter('nodeId', $formData['nodeId'])
            ->getQuery()
            ->getResult()[0]['count'];
    }
}
