<?php

namespace App\Repository;

use App\Entity\Clients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clients>
 *
 * @method Clients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clients[]    findAll()
 * @method Clients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clients::class);
    }

    public function save(Clients $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Clients $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllAlertsWithClientInfos(): array
    {
        return $this->createQueryBuilder('clients')
            ->select('clients, invoices, alerts') // Sélectionne les clients et factures, et compte le nombre d'alertes
            ->join('clients.invoices', 'invoices') // Jointure entre clients et invoices
            ->join('invoices.alerts', 'alerts') // Jointure entre invoices et alerts
            ->getQuery()
            ->getResult();
    }


    public function getClientsWithBalance(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c, COALESCE(SUM(a.balance), 0) as balance_accounts')
            ->leftJoin('c.accounts', 'a')
            ->groupBy('c.id');

        $results = $qb->getQuery()->getResult();

        $clientsList = [];

        foreach ($results as $result) {
            /** @var Client $client */
            $client = $result[0];
            $balance = $result['balance_accounts'];
            $client->setBalanceAccounts((float) $balance);

            $clientsList[] = $client;
        }

        return $clientsList;
    }
    //    /**
    //     * @return Clients[] Returns an array of Clients objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Clients
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
