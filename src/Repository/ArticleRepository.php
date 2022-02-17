<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return array<int, mixed>
     */
    public function getLastYearCountsPerMonths(): array
    {
        $date = new DateTime();
        $date->modify('-1 year');
        return $this->createQueryBuilder('a')
            ->select("DISTINCT DATE_FORMAT(a.posted_at,'%m/%Y') as formatted_date, COUNT(a.id) as count")
            ->addGroupBy("formatted_date")
            ->where('YEAR(a.posted_at) > :year')
            ->orWhere('YEAR(a.posted_at) = :year AND MONTH(a.posted_at) > :month')
            ->setParameter('year', $date->format('Y'))
            ->setParameter('month', $date->format('m'))
            ->orderBy('YEAR(a.posted_at)')
            ->addOrderBy('MONTH(a.posted_at)')
            ->getQuery()
            ->getResult();
    }
}
