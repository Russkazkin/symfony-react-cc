<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\AdvertStatus;
use App\Repository\AdvertRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdvertRepository::class)]
#[ORM\Table(name: '`adverts`')]
#[ApiResource]
class Advert
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'adverts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'adverts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?DateTimeImmutable $published_at = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'adverts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?int $view_count = null;

    #[ORM\Column(nullable: true)]
    private array $photos = [];

    #[ORM\Column(type: 'string', enumType: AdvertStatus::class)]
    private AdvertStatus $status;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'advert', targetEntity: ModerationRecord::class)]
    private Collection $moderationRecords;

    public function __construct()
    {
        $this->moderationRecords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?DateTimeImmutable
    {
        return $this->published_at;
    }

    public function setPublishedAt(DateTimeImmutable $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getViewCount(): ?int
    {
        return $this->view_count;
    }

    public function setViewCount(?int $view_count): self
    {
        $this->view_count = $view_count;

        return $this;
    }

    public function getPhotos(): array
    {
        return $this->photos;
    }

    public function setPhotos(?array $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function getStatus(): AdvertStatus
    {
        return $this->status;
    }

    public function setStatus(AdvertStatus $status): self
    {
        $this->status = $status;

        return $this;
    }


    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, ModerationRecord>
     */
    public function getModerationRecords(): Collection
    {
        return $this->moderationRecords;
    }

    public function addModerationRecord(ModerationRecord $moderationRecord): self
    {
        if (!$this->moderationRecords->contains($moderationRecord)) {
            $this->moderationRecords->add($moderationRecord);
            $moderationRecord->setAdvert($this);
        }

        return $this;
    }

    public function removeModerationRecord(ModerationRecord $moderationRecord): self
    {
        if ($this->moderationRecords->removeElement($moderationRecord)) {
            // set the owning side to null (unless already changed)
            if ($moderationRecord->getAdvert() === $this) {
                $moderationRecord->setAdvert(null);
            }
        }

        return $this;
    }
}
