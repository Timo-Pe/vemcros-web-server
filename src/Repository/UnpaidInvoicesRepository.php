<?php

namespace App\Repository;

use App\Entity\UnpaidInvoices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UnpaidInvoices>
 *
 * @method UnpaidInvoices|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnpaidInvoices|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnpaidInvoices[]    findAll()
 * @method UnpaidInvoices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnpaidInvoicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnpaidInvoices::class);
    }

    public function save(UnpaidInvoices $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UnpaidInvoices $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UnpaidInvoices[] Returns an array of UnpaidInvoices objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UnpaidInvoices
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
