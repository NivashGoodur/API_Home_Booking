<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Doctrine\Orm\Filter\RangeFilter;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ApiResource(
    normalizationContext : ['groups' => ['property_read'] ],
    denormalizationContext: ['groups' => ['property_write']],
    paginationItemsPerPage: 5
)]
#[ApiFilter(SearchFilter::class, properties: ['address' => 'partial', 'owner' => 'exact'])]
#[ApiFilter(RangeFilter::class, properties: ['pricePerNight'])]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('property_read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "Name is required.")]
    #[Length(
        max: 255,
        maxMessage: "The name cannot be longer than {{ limit }} characters."
    )]
    #[Groups(['property_read', 'property_write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[NotBlank(message: "Description is required.")]
    #[Groups(['property_read', 'property_write'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "Address is required.")]
    #[Length(
        max: 255,
        maxMessage: "The address cannot be longer than {{ limit }} characters."
    )]
    #[Groups(['property_read', 'property_write'])]
    private ?string $address = null;

    #[ORM\Column]
    #[NotBlank(message: "Price per night is required.")]
    #[Type(
        type: 'float',
        message: "The value {{ value }} is not a valid {{ type }}."
    )]
    #[Groups(['property_read', 'property_write'])]
    private ?float $pricePerNight = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'property', orphanRemoval: true)]
    private Collection $bookings;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'property', orphanRemoval: true)]
    private Collection $reviews;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'property')]
    #[Groups(['property_read', 'property_write'])]
    private Collection $photos;

    /**
     * @var Collection<int, Availability>
     */
    #[ORM\OneToMany(targetEntity: Availability::class, mappedBy: 'property')]
    #[Groups(['property_read', 'property_write'])]
    private Collection $availabilities;

    #[ORM\ManyToOne(inversedBy: 'properties')]
    #[ORM\JoinColumn(nullable: false)]
    #[NotNull(message: "Owner is required.")]
    #[Groups(['property_read', 'property_write'])]
    private ?User $owner = null;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->availabilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPricePerNight(): ?float
    {
        return $this->pricePerNight;
    }

    public function setPricePerNight(float $pricePerNight): static
    {
        $this->pricePerNight = $pricePerNight;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBookings(Booking $bookings): static
    {
        if (!$this->bookings->contains($bookings)) {
            $this->bookings->add($bookings);
            $bookings->setProperty($this);
        }

        return $this;
    }

    public function removeBookings(Booking $bookings): static
    {
        if ($this->bookings->removeElement($bookings)) {
            // set the owning side to null (unless already changed)
            if ($bookings->getProperty() === $this) {
                $bookings->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setProperty($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProperty() === $this) {
                $review->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setProperty($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getProperty() === $this) {
                $photo->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Availability>
     */
    public function getAvailabilities(): Collection
    {
        return $this->availabilities;
    }

    public function addAvailability(Availability $availability): static
    {
        if (!$this->availabilities->contains($availability)) {
            $this->availabilities->add($availability);
            $availability->setProperty($this);
        }

        return $this;
    }

    public function removeAvailability(Availability $availability): static
    {
        if ($this->availabilities->removeElement($availability)) {
            // set the owning side to null (unless already changed)
            if ($availability->getProperty() === $this) {
                $availability->setProperty(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
