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

    public function getSchedulesByProduct(Product $product): array
    {
        return $this->createQueryBuilder('ps')
            ->andWhere('ps.product = :product')
            ->setParameter('product', $product)
            ->getQuery()
            ->getResult();
    }
}

