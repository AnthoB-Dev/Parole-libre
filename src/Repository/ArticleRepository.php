<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
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

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /**
     * Récupère dans la bdd tous les articles ainsi que la category.name (récupérable avec article.name) et user.pseudo (récupérable avec article.pseudo)
     * 
     * @return array
     */
    public function findAllArticles(): ?array
    {
        return $this->createQueryBuilder("a")
            ->select("a.id", "a.title", "a.subtitle", "a.image", "a.content", "a.createdAt", "a.updatedAt", "category.name", "user.pseudo")
            ->join('a.user', 'user')
            ->join('a.category', 'category')
            ->orderBy("a.id", "ASC")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère dans la bdd tous les articles par catégorie, prend en paramètre l'id d'une catégorie (category.id)
     * 
     * @return array
     */
    public function findAllArticlesByCategoryId($value): ?array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.category = :category')
            ->setParameter('category', $value)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère dans la bdd 9 articles triés par date de creation, du plus récent au plus ancien. Prend en paramètre(s) une ou plusieurs catégories (category.id)
     * 
     * @return array
     */
    public function findArticlesByRecentlyPublishedAndByCategories(...$categories): ?array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.category IN (:categories)')
            ->setParameter('categories', $categories)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(9)
            ->getQuery()
            ->getResult()
        ;
    }


    // findArticlesByRecentlyPublishedAndByCategories(value, value)
    // findArticlesByRecentlyPublishedAndByCategory(value)
    // findArticlesByPopularityAndByCategory(value)
    // findSuperAuthors()
}
