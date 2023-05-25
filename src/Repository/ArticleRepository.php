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

    /**
     * Récupère dans la bdd $maxResults articles triés par date de creation, du plus récent au plus ancien. Prend en paramètre(s) une ou plusieurs id(s) de catégories (category.id)
     * 
     * @return array
     */
    public function findArticlesByRecentlyPublishedAndByCategories($maxResults, ...$categories): ?array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->addSelect('c')
            ->andWhere('a.category IN (:categories)')
            ->setParameter('categories', $categories)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère dans la bdd $maxResults articles triés par date de creation, du plus récent au plus ancien. 
     * Prend en paramètre l'id d'une catégorie (category.id)
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
     * Prend en paramètres $maxResults qui sera le nb d'article par ...$categories à afficher, ...$categories étant les id des catégories voulant être fetch. 
     * La fonction bouclera ensuite sur chaque id et récupèrera les données grâce à findArticlesByRecentlyPublishedAndByCategory()
     * 
     * @return array
     */
    public function findArticlesRecentlyPublishedByCategories($maxResults, ...$categories): ?array
    {
        $articles = [];
        foreach ($categories as $category) {
            $articles[] = $this->findArticlesByRecentlyPublishedAndByCategory($maxResults, $category);
        }
        return $articles;
    }
    
    // findArticlesByPopularityAndByCategory(value)
    // findSuperAuthors()
}
