<?php

namespace App\Entity;

use App\Repository\ArticleCommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleCommentRepository::class)]
class ArticleComment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'articleComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'articleComments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?article $article = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'article_comment', targetEntity: CommentLike::class, orphanRemoval: true)]
    private Collection $commentLikes;

    #[ORM\OneToMany(mappedBy: 'article_comment', targetEntity: Report::class)]
    private Collection $reports;

    public function __construct()
    {
        $this->commentLikes = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getArticle(): ?article
    {
        return $this->article;
    }

    public function setArticle(?article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, CommentLike>
     */
    public function getCommentLikes(): Collection
    {
        return $this->commentLikes;
    }

    public function addCommentLike(CommentLike $commentLike): self
    {
        if (!$this->commentLikes->contains($commentLike)) {
            $this->commentLikes->add($commentLike);
            $commentLike->setArticleComment($this);
        }

        return $this;
    }

    public function removeCommentLike(CommentLike $commentLike): self
    {
        if ($this->commentLikes->removeElement($commentLike)) {
            if ($commentLike->getArticleComment() === $this) {
                $commentLike->setArticleComment(null);
            }
        }

        return $this;
    }

    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setArticleComment($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->removeElement($report)) {
            
            if ($report->getArticleComment() === $this) {
                $report->setArticleComment(null);
            }
        }

        return $this;
    }

    public function getCommentLikesCount(): int
    {
        /** @var Collection<int, CommentLike> $commentLikes */
        $commentLikes = $this->getCommentLikes();

        return $commentLikes->count();
    }
}
