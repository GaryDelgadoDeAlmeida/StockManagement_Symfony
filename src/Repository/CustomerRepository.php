<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function add(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Customer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int offset
     * @param int limit
     * @return Customer[] Return an array of Customer object
     */
    public function getCustomers(int $offset, int $limit)
    {
        return $this->createQueryBuilder("c")
            ->orderBy("c.lastname", "ASC", "c.firstname", "ASC")
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string value
     * @return Customer[]
     */
    public function searchCustomers(string $value)
    {
        return $this->createQueryBuilder("c")
            ->where("c.firstname LIKE :value")
            ->orWhere("c.lastname LIKE :value")
            ->orWhere("c.email LIKE :value")
            ->setParameter("value", "%{$value}%")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return int Return the number of customers
     */
    public function countCustomers()
    {
        return $this->createQueryBuilder("c")
            ->select("COUNT(c.id) as nbrCustomers")
            ->getQuery()
            ->getSingleResult()["nbrCustomers"]
        ;
    }

    /**
     * @param string value
     * @return int
     */
    public function countSearchedCustomer(string $value)
    {
        return $this->createQueryBuilder("c")
            ->select("COUNT(c.id) as nbrCustomers")
            ->where("c.firstname LIKE :value")
            ->orWhere("c.lastname LIKE :value")
            ->orWhere("c.email LIKE :value")
            ->setParameter("value", "%{$value}%")
            ->getQuery()
            ->getSingleResult()["nbrCustomers"]
        ;
    }
}
