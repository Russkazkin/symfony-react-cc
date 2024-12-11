<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Enum\ModerationRecordStatus;
use App\Repository\ModerationRecordRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModerationRecordRepository::class)]
#[ORM\Table(name: '`moderation_records`')]
#[ApiResource]
class ModerationRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?DateTimeImmutable $moderated_at = null;

    #[ORM\ManyToOne(inversedBy: 'moderationRecords')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Advert $advert = null;

    #[ORM\ManyToOne(inversedBy: 'moderationRecords')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'string', enumType: ModerationRecordStatus::class)]
    private ModerationRecordStatus $status;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModeratedAt(): ?DateTimeImmutable
    {
        return $this->moderated_at;
    }

    public function setModeratedAt(DateTimeImmutable $moderated_at): self
    {
        $this->moderated_at = $moderated_at;

        return $this;
    }

    public function getAdvert(): ?Advert
    {
        return $this->advert;
    }

    public function setAdvert(?Advert $advert): self
    {
        $this->advert = $advert;

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

    public function getStatus(): ModerationRecordStatus
    {
        return $this->status;
    }

    public function setStatus(ModerationRecordStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
