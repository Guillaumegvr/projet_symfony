<?php

namespace App\Repository;

use App\Entity\Wish;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wish>
 */
class WishRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wish::class);
    }

    public function findPublishWishWithCategory():?array
    {
        $queryBuilder = $this->createQueryBuilder('w');
        $queryBuilder -> join("w.category", 'c')
            -> addSelect ('c');
        $queryBuilder -> orderBy('w.dateCreated', 'DESC');
        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }


}
