<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function add(Product &$entity, bool $flush = false): void
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
     * @param int offset
     * @param int limit
     * @return Product[] Return an array of Product object
     */
    public function getProducts(int $offset, int $limit)
    {
        return $this->createQueryBuilder("p")
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int offset
     * @param int limit
     * @return Product[]
     */
    public function getBestSelledProducts(int $offset, int $limit)
    {
        return $this->createQueryBuilder("p")
            ->leftJoin("p.productOrders", "prdOrder")
            ->where("prdOrder.quantity = MAX(prdOrder.quantity)")
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int entity id
     * @param int offset
     * @param int limit
     * @return Product[]
     */
    public function getProductsByEntityID(int $entityID, int $offset, int $limit)
    {
        return $this->createQueryBuilder("p")
            ->where("p.entityID = :entityID")
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->setParameter("entityID", $entityID)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Get products with low storage
     */
    public function getLowStorageProduct(int $offset, int $limit)
    {
        return $this->createQueryBuilder("p")
            ->where("p.stock <= 5")
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string value
     */
    public function searchProduct(string $value)
    {
        return $this->createQueryBuilder("p")
            ->where("p.name LIKE :value")
            ->setParameter("value", "%{$value}%")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int Return the number of products
     */
    public function countProducts()
    {
        return $this->createQueryBuilder("p")
            ->select("COUNT(p.id) as nbrProducts")
            ->getQuery()
            ->getSingleResult()["nbrProducts"]
        ;
    }

    /**
     * @return int
     */
    public function countLowStorageProduct()
    {
        return $this->createQueryBuilder("p")
            ->select("COUNT(p.id) as nbrProducts")
            ->getQuery()
            ->getSingleResult()["nbrProducts"]
        ;
    }

    /**
     * @param int entity id
     * @return int
     */
    public function countProductsByEntityID(int $entityID)
    {
        return $this->createQueryBuilder("p")
            ->select("COUNT(p.id) as nbrProducts")
            ->where("p.entityID = :entityID")
            ->setParameter("entityID", $entityID)
            ->getQuery()
            ->getSingleResult()["nbrProducts"]
        ;
    }
}
