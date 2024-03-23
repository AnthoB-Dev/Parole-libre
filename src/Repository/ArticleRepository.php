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
     * Récupère tous les articles, triés par date de creation du plus récent au plus ancien ainsi que la category.name et user.pseudo.
     * 
     * @param string orderBy DESC
     * @return array|null
     */
    public function findAllArticles(): ?array
    {
        return $this->createQueryBuilder("a")
            ->select("a.id", "a.title", "a.subtitle", "a.image", "a.content", "a.createdAt", "a.updatedAt", "category.name", "user.pseudo")
            ->join('a.user', 'user')
            ->join('a.category', 'category')
            ->orderBy("a.id", "DESC")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère tous les articles d'une catégorie $categoryId, triés par date de creation du plus récent au plus ancien et où paroleLibre = true ou false.
     * 
     * @param int $categoryId
     * @param bool $paroleLibre false (default)
     * @param string orderBy DESC
     * @return array|null
     */
    public function findAllArticlesByCategoryId(int $categoryId, bool $paroleLibre = false): ?array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.category = :category')
            ->andWhere('a.paroleLibre = :paroleLibre')
            ->setParameter('category', $categoryId)
            ->setParameter('paroleLibre', $paroleLibre)
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère tous les articles, triés par date de creation du plus récent au plus ancien et où paroleLibre = true.
     * 
     * @param bool article.paroleLibre true
     * @param string orderBy DESC 
     * @return array|null
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
     * Récupère $maxResults articles selon un tableau d'ids de catégories $categories, triés par date de creation du plus récent au plus ancien et où paroleLibre = false.
     * Ce qui permet de récupérer $maxResults articles de charque catégories existante, modulo ParoleLibre puisque défini en false.
     * 
     * @param int $maxResults
     * @param array $categories
     * @param bool article.paroleLibre false
     * @param string orderBy DESC
     * @return array|null
     */
    public function findArticlesByRecentlyPublishedAndByCategories(int $maxResults, array $categories): ?array
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
     * Récupère $maxResults articles par leurs date de publication et où paroleLibre = true.
     * 
     * @param int $maxResults
     * @param bool article.paroleLibre true
     * @param string orderBy DESC
     * @return array|null
     */
    public function findArticlesByRecentlyPublishedAndByParoleLibre(int $maxResults): ?array
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
     * Récupère $maxResults articles d'une catégorie $categoryId, triés par date de creation du plus récent au plus ancien et où paroleLibre = true ou false.
     * 
     * @param int $maxResults
     * @param int $categoryId
     * @param bool $paroleLibre false (default)
     * @param string orderBy DESC
     * @return array|null
     */
    public function findArticlesByCategory(int $maxResults, int $categoryId, bool $paroleLibre = false): ?array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->addSelect('c')
            ->andWhere('a.category = :category')
            ->andWhere('a.paroleLibre = :paroleLibre')
            ->setParameter('category', $categoryId)
            ->setParameter('paroleLibre', $paroleLibre)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère $maxResults articles d'une catégorie $categoryId, triés par date de creation du plus récent au plus ancien et où paroleLibre = true.
     * 
     * @param int $maxResults
     * @param int $categoryId
     * @param bool article.paroleLibre true
     * @param string orderBy DESC 
     * @return array|null
     */
    public function findArticlesByCategoryAndParoleLibre(int $maxResults, int $categoryId): ?array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->addSelect('c')
            ->andWhere('a.category = :category')
            ->andWhere('a.paroleLibre = :paroleLibre')
            ->setParameter('category', $categoryId)
            ->setParameter('paroleLibre', true)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Récupère $maxResults articles par id présent dans le tableau d'ids $categories.\
     * La fonction boucle sur chaque id puis récupère, stock dans le tableau $article grâce à la fonction findArticlesByCategory(), et renvoie ces données.
     * 
     * @param int $maxResults
     * @param array $categories
     * @param callable findArticlesByCategory
     * @return array|null $articles
     */
    public function findArticlesRecentlyPublishedByCategories(int $maxResults, array $categories): ?array
    {
        $articles = [];
        foreach ($categories as $category) {
            $articles[] = $this->findArticlesByCategory($maxResults, $category);
        }
        return $articles;
    }

    /**
     * Récupère tous les articles, parole libre ou non, d'une catégorie $categoruId selon leurs popularité (nombre de likes).
     * 
     * @param bool IN_USE false
     * @param int $maxResults
     * @param int $categoryId
     * @param string orderBy nombre de likes DESC
     * @return array|null
     */
    public function findByPopularityOfCategory(int $maxResults, int $categoryId)
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
     * Récupère tous les articles, parole libre ou non, des catégories $categories selon leurs popularité (nombre de likes).
     * 
     * @param int $maxResults
     * @param array $categories
     * @param callable findByPopularityOfCategory
     * @return array|null $articles
     */
    public function findByPopularityOfCategories(int $maxResults, array $categories): ?array
    {
        $articles = [];
        foreach ($categories as $category) {
            $articles[] = $this->findByPopularityOfCategory($maxResults, $category);
        }
        return $articles;
    }

    /**
     * Récupère l'article par rapport à la date de publication de son dernier commentaire.
     * 
     * @param int $maxResults
     * @param string orderBy DESC
     * @return array|null
     */
    public function findArticlesByRecentComments(int $maxResults): ?array
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

    /**
     * Récupère les articles d'une catégorie où paroleLibre = true ou false ainsi que ses commentaires récents.
     * 
     * @param int $maxResults
     * @param int $categoryId
     * @param bool $paroleLibre false (default)
     * @param string orderBy DESC 
     * @return array|null
     */
    public function findArticlesByCategoryAndRecentComments(int $maxResults, int $categoryId, bool $paroleLibre = false): ?array
    {
        return $this->createQueryBuilder('a')
            ->select("a.id, c.name, a.title, a.titleSlug, ac.id as cId, ac.content, ac.createdAt, c.categorySlug, u.pseudo")
            ->join("a.articleComments", "ac")
            ->join("ac.user", "u")
            ->join("a.category", "c")
            ->where("c.id = :category")
            ->andWhere("a.paroleLibre = :paroleLibre")
            ->setParameter("paroleLibre", $paroleLibre)
            ->setParameter("category", $categoryId)
            ->orderBy('ac.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
        ;
    }

    /**
     * Récupère tous les articles d'une catégorie où paroleLibre = true ainsi que ses commentaires récents.\
     * Ce sera les commentaires de la sidebar affichés sur la route /categorie/parole-libre/politique par exemple.
     * 
     * @param int $maxResults
     * @param int $categoryId
     * @param bool article.paroleLibre true
     * @param string orderBy DESC 
     * @return array|null
     */
    public function findArticlesParoleLibreByCategoryAndRecentComments(int $maxResults, int $categoryId): ?array
    {
        return $this->createQueryBuilder('a')
            ->select("a.id, c.name, a.title, a.titleSlug, ac.id as cId, ac.content, ac.createdAt, c.categorySlug, u.pseudo")
            ->join("a.articleComments", "ac")
            ->join("ac.user", "u")
            ->join("a.category", "c")
            ->where("c.id = :category")
            ->andWhere("a.paroleLibre = :paroleLibre")
            ->setParameter("paroleLibre", true)
            ->setParameter("category", $categoryId)
            ->orderBy('ac.createdAt', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
        ;
    }

    /**
     * Récupères tous les articles où paroleLibre = true ainsi que ses commentaires récents.\
     * Ce sera les commentaires de la  sidebar affichés sur la route /categorie/parole-libre
     * 
     * @param int $maxResults
     * @param bool article.paroleLibre true
     * @param string orderBy DESC 
     * @return array|null
     */
    public function findParolesLibresAndRecentComments(int $maxResults): ?array
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
    
    // TODO: findSuperAuthors()
}
