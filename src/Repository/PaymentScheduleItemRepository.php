<?php

namespace App\Repository;

use App\Entity\PaymentScheduleItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentScheduleItem>
 */
class PaymentScheduleItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentScheduleItem::class);
    }
}
