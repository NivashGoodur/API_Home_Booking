<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use App\Repository\PhotoRepository;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints\Url;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
#[ApiResource(
    normalizationContext : ['groups' => ['photo_read'] ],
    denormalizationContext: ['groups' => ['photo_write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['property' => 'exact'])]
class Photo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('photo_read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: "URL is required.")]
    #[Url(message: "The URL '{{ value }}' is not a valid URL.")]
    #[Groups(['photo_read', 'photo_write'])]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    #[NotNull(message: "Property is required.")]
    #[Groups(['photo_read', 'photo_write'])]
    private ?Property $property = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

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
