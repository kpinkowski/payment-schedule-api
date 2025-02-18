<?php

namespace App\Repository;

use App\Entity\PaymentSchedule;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentSchedule>
 */
class PaymentScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentSchedule::class);
    }

    public function getSchedule(int $scheduleId): ?PaymentSchedule
    {
       return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.id = :id')
            ->setParameter('id', $scheduleId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

