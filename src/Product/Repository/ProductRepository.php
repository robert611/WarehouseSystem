<?php

namespace App\Product\Repository;

use App\Product\Entity\Product;
use App\Product\Form\DTO\ProductSearchEngineDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function add(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Product[]
     */
    public function searchEngineResults(ProductSearchEngineDTO $productSearchEngineFormDTO): array
    {
        $queryBuilder = $this->createQueryBuilder('product')
            ->select('product');

        $this->addSearchEngineRequirements($queryBuilder, $productSearchEngineFormDTO);

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    private function addSearchEngineRequirements(QueryBuilder $queryBuilder, ProductSearchEngineDTO $formDTO): void
    {
        if ($formDTO->getName()) {
            $queryBuilder->andWhere('product.name = :name');
            $queryBuilder->setParameter('name', $formDTO->getName());
        }
    }
}
