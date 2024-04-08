<?php

namespace App\Repository;

use App\Entity\ReportReason;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReportReason>
 *
 * @method ReportReason|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportReason|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportReason[]    findAll()
 * @method ReportReason[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportReasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportReason::class);
    }

    public function save(ReportReason $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReportReason $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
