<?php

namespace App\Repository;

use App\Entity\CoStudying;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CoStudying|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoStudying|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoStudying[]    findAll()
 * @method CoStudying[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoStudyingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoStudying::class);
    }

    public function OrderByName()
    {
        return $this->createQueryBuilder('CoStudying')
            ->orderBy('CoStudying.rating','DESC')
            ->getQuery()->getResult();
        $this->addFlash('success', 'Tri affectué!');
    }

    public function OrderByRating()
    {
        return $this->createQueryBuilder('CoStudying')
            ->orderBy('CoStudying.type','DESC')
            ->getQuery()->getResult();
        $this->addFlash('success', 'Tri affectué!');
    }




    // /**
    //  * @return CoStudying[] Returns an array of CoStudying objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CoStudying
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
