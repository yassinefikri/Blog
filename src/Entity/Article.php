<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'Your first name must be at least {{ limit }} characters long',
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )]
    private ?string $title;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $content;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $posted_at;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $posted_by;

    public function __construct()
    {
        $this->posted_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getPostedAt(): ?DateTimeImmutable
    {
        return $this->posted_at;
    }

    public function setPostedAt(DateTimeImmutable $posted_at): self
    {
        $this->posted_at = $posted_at;

        return $this;
    }

    public function getPostedBy(): ?User
    {
        return $this->posted_by;
    }

    public function setPostedBy(?User $posted_by): self
    {
        $this->posted_by = $posted_by;

        return $this;
    }
}
