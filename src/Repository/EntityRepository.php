<?php

namespace App\Repository;

use App\Entity\Entity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entity>
 *
 * @method Entity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity[]    findAll()
 * @method Entity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entity::class);
    }

    public function add(Entity &$entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Entity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param int page
     * @param int Limit of Entity objects per pages
     * @return Entity[] Return an array of Entity objects
     */
    public function getEntities(int $offset, int $limit = 15)
    {
        return $this->createQueryBuilder("e")
            ->orderBy("e.name", "ASC")
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param string siret
     * @return Entity Return an Entity object
     */
    public function getEntityBySiret(string $siret)
    {
        return $this->createQueryBuilder("e")
            ->select("e.id, e.name, e.socialName, e.siret, e.siren, e.address, e.zipCode, e.city, e.country, e.createdAt")
            ->where("e.siret LIKE :entitySiret")
            ->setParameter(":entitySiret", $siret)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return int Return the number of entities
     */
    public function countEntities()
    {
        return $this->createQueryBuilder("e")
            ->select("COUNT(e.id) as nbrEntities")
            ->getQuery()
            ->getSingleResult()["nbrEntities"]
        ;
    }
}
