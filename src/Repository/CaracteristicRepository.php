<?php

namespace App\Repository;

use App\Entity\Caracteristic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Caracteristic>
 *
 * @method Caracteristic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Caracteristic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Caracteristic[]    findAll()
 * @method Caracteristic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CaracteristicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Caracteristic::class);
    }

    public function add(Caracteristic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Caracteristic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
