<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\BookingRepository;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use App\Controller\BookingValidationController;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ApiResource(
    normalizationContext : ['groups' => ['booking_read'] ],
    denormalizationContext: ['groups' => ['booking_write']],
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Put(),
        new Delete(),
        new Patch(),
        new Patch(
            name: 'validate',
            uriTemplate: '/booking/{id}/validate', 
            controller: BookingValidationController::class, 
            read: false,
            status: 200,
            openapiContext: [
                'summary' => 'Validate a booking',
                'description' => 'This operation allows you to validate a booking by setting its status to confirmed.'
            ]
        )
    ],
)]
#[ApiFilter(SearchFilter::class, properties: ['property' => 'exact', 'tenant' => 'exact', 'status' => 'exact'])]
#[ApiFilter(DateFilter::class, properties: ['startDate', 'endDate'])]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('booking_read')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    #[NotNull(message: "Property is required.")]
    #[Groups(['booking_read', 'booking_write'])]
    private ?Property $property = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    #[NotNull(message: "Tenant is required.")]
    #[Groups(['booking_read', 'booking_write'])]
    private ?User $tenant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotBlank(message: "Start date is required.")]
    #[Type(type: \DateTimeInterface::class, message: "This value should be of type DateTimeInterface.")]
    #[Groups(['booking_read', 'booking_write'])]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[NotBlank(message: "End date is required.")]
    #[Type(type: \DateTimeInterface::class, message: "This value should be of type DateTimeInterface.")]
    #[Groups(['booking_read', 'booking_write'])]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(length: 255)]
    #[Groups(['booking_read', 'booking_write'])]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTenant(): ?User
    {
        return $this->tenant;
    }

    public function setTenant(?User $tenant): static
    {
        $this->tenant = $tenant;

        return $this;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
