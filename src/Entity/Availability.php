<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\AvailabilityRepository;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: AvailabilityRepository::class)]
#[ApiResource(
    normalizationContext : ['groups' => ['availability_read'] ],
    denormalizationContext: ['groups' => ['availability_write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['property' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['startDate', 'endDate'])]
class Availability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('availability_read')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotBlank(message: "Start date is required.")]
    #[Type(type: \DateTimeInterface::class, message: "This value should be of type DateTimeInterface.")]
    #[Groups(['availability_read', 'availability_write'])]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotBlank(message: "End date is required.")]
    #[Type(type: \DateTimeInterface::class, message: "This value should be of type DateTimeInterface.")]
    #[Groups(['availability_read', 'availability_write'])]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\ManyToOne(inversedBy: 'availabilities')]
    #[ORM\JoinColumn(nullable: false)]
    #[NotNull(message: "Property is required.")]
    #[Groups(['availability_read', 'availability_write'])]
    private ?Property $property = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

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
}
