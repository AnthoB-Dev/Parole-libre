<?php

namespace App\Repository;

use App\Entity\ArticleComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleComment>
 *
 * @method ArticleComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleComment[]    findAll()
 * @method ArticleComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleComment::class);
    }

    public function save(ArticleComment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ArticleComment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findComments(int $articleId): ?array
    {
        return $this->createQueryBuilder("ac")
            ->select("ac.id,
                      a.id AS aId,
                      c.categorySlug AS cSlug,
                      a.titleSlug as aTitleSlug,
                      ac.content,
                      ac.createdAt,
                      ac.updatedAt,
                      u.pseudo AS uPseudo,
                      u.id AS uId,
                      COUNT(DISTINCT l.id) AS commentLikesCount")
            ->leftJoin("ac.user", "u")
            ->leftJoin("ac.commentLikes", "l")
            ->leftJoin("ac.article", "a")
            ->leftJoin("a.category", "c")
            ->where("a.id = :articleId")
            ->setParameter("articleId", $articleId)
            ->groupBy("ac.id")
            ->orderBy('ac.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
