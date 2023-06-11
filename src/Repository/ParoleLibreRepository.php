<?php

namespace App\Repository;

use App\Entity\ParoleLibre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParoleLibre>
 *
 * @method ParoleLibre|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParoleLibre|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParoleLibre[]    findAll()
 * @method ParoleLibre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParoleLibreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParoleLibre::class);
    }

    public function save(ParoleLibre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ParoleLibre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Récupère dans la bdd $maxResults articles triés par date de creation, du plus récent au plus ancien. 
     * Prend en second paramètre l'id d'une catégorie (category.id)
     * 
     * @return array
     */
    public function findArticlesByRecentlyPublishedAndByCategory($maxResults, $category): ?array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->addSelect('c')
            ->andWhere('a.category = :category')
            ->setParameter('category', $category)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère dans la bdd tous les articles par catégorie, triés par date de creation, du plus récent au plus ancien. Prend en paramètre l'id d'une catégorie (category.id)
     * 
     * @return array
     */
    public function findAllArticlesByCategoryId($categoryId): ?array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.category = :category')
            ->setParameter('category', $categoryId)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findArticlesByCategoryAndRecentComments($maxResults, $categoryId): ?array
    {
        return $this->createQueryBuilder('a')
            ->select("a.id, c.name, a.title, a.titleSlug, ac.id as cId, ac.content, ac.createdAt, c.categorySlug, u.pseudo")
            ->join("a.articleComments", "ac")
            ->join("ac.user", "u")
            ->join("a.category", "c")
            ->where("c.id = :category")
            ->setParameter("category", $categoryId)
            ->orderBy('ac.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
        ;
    }
}
