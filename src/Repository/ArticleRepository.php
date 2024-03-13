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
            ->andWhere('a.paroleLibre = :paroleLibre')
            ->setParameter('category', $categoryId)
            ->setParameter('paroleLibre', false)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * 
     * @return array
     */
    public function findAllArticlesOfParoleLibre(): ?array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.paroleLibre = :paroleLibre')
            ->setParameter('paroleLibre', true)
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
            ->andWhere('a.paroleLibre = :paroleLibre')
            ->setParameter('categories', $categories)
            ->setParameter('paroleLibre', false)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
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
            ->andWhere('a.paroleLibre = :paroleLibre')
            ->setParameter('category', $category)
            ->setParameter('paroleLibre', false)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * 
     * 
     * @return array
     */
    public function findArticlesByRecentlyPublishedAndByParoleLibre($maxResults): ?array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->addSelect('c')
            ->andWhere('a.paroleLibre = :paroleLibre')
            ->setParameter('paroleLibre', true)
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

    /**
     * Récupère dans la bdd $maxResults articles triés par le nombre de likes qu'ils possèdent. 
     * Prend en second paramètre l'id d'une catégorie (category.id)
     * 
     * @return array
     */
    public function findByPopularityOfCategory($maxResults, $categoryId)
    {
        return $this->createQueryBuilder("a")
            ->leftJoin("a.category", "c")
            ->leftJoin("a.articleLikes", "l")
            ->andWhere("c.id = :category")
            ->setParameter("category", $categoryId)
            ->groupBy("a.id")
            ->orderBy("COUNT(l.id)", "DESC")
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Prend en paramètres $maxResults qui sera le nb d'article par ...$categories à afficher, ...$categories étant les id des catégories voulant être fetch. 
     * La fonction bouclera ensuite sur chaque id et récupèrera les données grâce à findByPopularityOfCategory()
     * 
     * @return array
     */
    public function findByPopularityOfCategories($maxResults, ...$categories): ?array
    {
        $articles = [];
        foreach ($categories as $category) {
            $articles[] = $this->findByPopularityOfCategory($maxResults, $category);
        }
        return $articles;
    }

    public function findArticlesByRecentComments($maxResults): ?array
    {
        return $this->createQueryBuilder('a')
            ->select("a.id, c.name, a.title, a.titleSlug, ac.id as cId, ac.content, ac.createdAt, c.categorySlug, u.pseudo")
            ->leftJoin("a.articleComments", "ac")
            ->leftJoin("ac.user", "u")
            ->leftJoin("a.category", "c")
            ->orderBy('ac.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
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

    public function findParolesLibresAndRecentComments($maxResults): ?array
    {
        return $this->createQueryBuilder('a')
            ->select("a.id, c.name, a.title, a.titleSlug, ac.id as cId, ac.content, ac.createdAt, c.categorySlug, u.pseudo")
            ->join("a.articleComments", "ac")
            ->join("ac.user", "u")
            ->join("a.category", "c")
            ->andWhere("a.paroleLibre = :paroleLibre")
            ->setParameter("paroleLibre", true)
            ->orderBy('ac.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
        ;
    }

    
    // findSuperAuthors()
}
