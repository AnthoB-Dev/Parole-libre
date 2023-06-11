<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?ArticleComment $article_comment = null;

    #[ORM\ManyToMany(targetEntity: reportReason::class, inversedBy: 'reports')]
    private Collection $report_reason;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $reportDate = null;

    public function __construct()
    {
        $this->report_reason = new ArrayCollection();
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

    public function getArticleComment(): ?articleComment
    {
        return $this->article_comment;
    }

    public function setArticleComment(?articleComment $article_comment): self
    {
        $this->article_comment = $article_comment;

        return $this;
    }

    /**
     * @return Collection<int, ReportReason>
     */
    public function getReportReason(): Collection
    {
        return $this->report_reason;
    }

    public function addReportReason(ReportReason $reportReason): self
    {
        if (!$this->report_reason->contains($reportReason)) {
            $this->report_reason->add($reportReason);
        }

        return $this;
    }

    public function removeReportReason(ReportReason $reportReason): self
    {
        $this->report_reason->removeElement($reportReason);

        return $this;
    }

    public function getReportDate(): ?\DateTimeInterface
    {
        return $this->reportDate;
    }

    public function setReportDate(\DateTimeInterface $reportDate): self
    {
        $this->reportDate = $reportDate;

        return $this;
    }
}
