<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\ReviewRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ApiResource(
    normalizationContext : ['groups' => ['review_read'] ],
    denormalizationContext: ['groups' => ['review_write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['property' => 'exact', 'tenant' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['rating'])]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('review_read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[NotNull(message: "Tenant is required.")]
    #[Groups(['review_read', 'review_write'])]
    private ?User $tenant = null;

    #[ORM\ManyToOne(inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    #[NotNull(message: "Property is required.")]
    #[Groups(['review_read', 'review_write'])]
    private ?Property $property = null;

    #[ORM\Column]
    #[NotBlank(message: "Rating is required.")]
    #[Range(
        min: 1,
        max: 5,
        notInRangeMessage: "You must be between {{ min }} and {{ max }} to rate this property."
    )]
    #[Groups(['review_read', 'review_write'])]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['review_read', 'review_write'])]
    private ?string $comment = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTenant(): ?User
    {
        return $this->tenant;
    }

    public function setTenant(?User $tenant): static
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }
}
