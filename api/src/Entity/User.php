<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Contracts\Timestampable;
use App\Enum\UserStatus;
use App\Repository\UserRepository;
use App\State\UserLoginProcessor;
use App\State\UserPasswordHasherProcessor;
use App\Traits\HasTimestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
#[UniqueEntity(fields: ['email', 'phone'])]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_USER')"),
        new Post(uriTemplate: '/registration',
            processor: UserPasswordHasherProcessor::class),
        new Post(uriTemplate: '/login',
            processor: UserLoginProcessor::class
        ),
        new Get(security: "is_granted('ROLE_USER')"),
        new Patch(normalizationContext: ['groups' => ['user:passwordRead']],
            denormalizationContext: ['groups' => ['user:passwordUpdate']],
            security: "object == user",
            securityMessage: 'Пароль можно менять только себе',
            processor: UserPasswordHasherProcessor::class),
        new Delete(security: "object == user", securityMessage: 'Удалять можно только себя'),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create']]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, Timestampable
{
    use HasTimestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['user:create', 'user:update'])]
    #[ORM\Column(length: 50)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Vasilii'
        ]
    )]
    private ?string $name = null;

    #[Groups(['user:create', 'user:update'])]
    #[ORM\Column(length: 50)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'Pupkin'
        ]
    )]
    private ?string $surname = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $patronymic = null;

    #[Groups(['user:create', 'user:update'])]
    #[ORM\Column(length: 180, unique: true)]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => 'vp@example.com'
        ]
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create', 'user:update'])]
    #[SerializedName('password')]
    #[ApiProperty(
        openapiContext: [
            'type' => 'string',
            'example' => '123456'
        ]
    )]
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $call_time = null;

    #[ORM\Column(type: 'string', nullable: false, enumType: UserStatus::class, options: ['default' => UserStatus::Pending])]
    private UserStatus $status = UserStatus::Pending;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Advert::class)]
    private Collection $adverts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ModerationRecord::class)]
    private Collection $moderationRecords;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read'])]
    private ?string $token = null;

    public function __construct()
    {
        $this->adverts = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPatronymic(): ?string
    {
        return $this->patronymic;
    }

    public function setPatronymic(?string $patronymic): self
    {
        $this->patronymic = $patronymic;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCallTime(): ?string
    {
        return $this->call_time;
    }

    public function setCallTime(?string $call_time): self
    {
        $this->call_time = $call_time;

        return $this;
    }

    public function getStatus(): UserStatus
    {
        return $this->status;
    }

    public function setStatus(UserStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Collection<int, Advert>
     */
    public function getAdverts(): Collection
    {
        return $this->adverts;
    }

    public function addAdvert(Advert $advert): self
    {
        if (!$this->adverts->contains($advert)) {
            $this->adverts->add($advert);
            $advert->setUser($this);
        }

        return $this;
    }

    public function removeAdvert(Advert $advert): self
    {
        if ($this->adverts->removeElement($advert)) {
            // set the owning side to null (unless already changed)
            if ($advert->getUser() === $this) {
                $advert->setUser(null);
            }
        }

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
            $moderationRecord->setUser($this);
        }

        return $this;
    }

    public function removeModerationRecord(ModerationRecord $moderationRecord): self
    {
        if ($this->moderationRecords->removeElement($moderationRecord)) {
            // set the owning side to null (unless already changed)
            if ($moderationRecord->getUser() === $this) {
                $moderationRecord->setUser(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
