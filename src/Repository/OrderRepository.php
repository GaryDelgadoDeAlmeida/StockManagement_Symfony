<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function add(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int offset
     * @param int limit
     * @return Order[]
     */
    public function getOrders(int $offset, int $limit)
    {
        return $this->createQueryBuilder("o")
            ->orderBy("o.id", "DESC")
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int customer id
     * @return Order[]
     */
    public function getOrdersByCustomerID(int $customerID)
    {
        return $this->createQueryBuilder("o")
            ->leftJoin("o.customerID", "c")
            ->where("c.id = :customerID")
            ->andWhere("o.status != :status")
            ->orderBy("o.id", "DESC")
            ->setParameter("customerID", $customerID)
            ->setParameter("status", "DELIVERED")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param int customer id
     * @return Order[]
     */
    public function getPastOrdersByCustomerID(int $customerID)
    {
        return $this->createQueryBuilder("o")
            ->leftJoin("o.customerID", "c")
            ->where("c.id = :customerID")
            ->andWhere("o.status = :status")
            ->orderBy("o.id", "DESC")
            ->setParameter("customerID", $customerID)
            ->setParameter("status", "DELIVERED")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int Return the number of orders
     */
    public function countOrders()
    {
        return $this->createQueryBuilder("o")
            ->select("COUNT(o.id) as nbrOrders")
            ->getQuery()
            ->getSingleResult()["nbrOrders"]
        ;
    }
}
