<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "Email is required.")]
    #[Assert\Email(message: "Please enter a valid email address.")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    // #[Assert\NotBlank(message: "Password is required.")]
    // #[Assert\Length(
    //     min: 8,
    //     minMessage: "Your password must be at least {{ limit }} characters long."
    // )]
    private ?string $password = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Assert\NotBlank(message: "Phone number is required.")]
    #[Assert\Regex(
        pattern: "/^\d{8,13}$/",
        message: "Phone number must contain between 8 and 13 digits."
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "First name is required.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "First name cannot be longer than {{ limit }} characters."
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Last name is required.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Last name cannot be longer than {{ limit }} characters."
    )]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "Birthdate is required.")]
    #[Assert\LessThanOrEqual(
        value: "-18 years",
        message: "You must be at least 18 years old to register."
    )]
    private ?\DateTimeInterface $birthDate = null;

    /**
     * @var Collection<int, Reponse>
     */
    #[ORM\OneToMany(targetEntity: Reponse::class, mappedBy: 'user_id')]
    private Collection $reponses;

    /**
     * @var Collection<int, Enchere>
     */
    #[ORM\OneToMany(targetEntity: Enchere::class, mappedBy: 'id_gagnant')]
    private Collection $encheres;

    /**
     * @var Collection<int, ProduitStore>
     */
    #[ORM\OneToMany(targetEntity: ProduitStore::class, mappedBy: 'agriculteur_id')]
    private Collection $produitStores;

    /**
     * @var Collection<int, ProduitEnchere>
     */
    #[ORM\OneToMany(targetEntity: ProduitEnchere::class, mappedBy: 'agriculteur_id')]
    private Collection $produitEncheres;

    /**
     * @var Collection<int, Enchere>
     */
    #[ORM\OneToMany(targetEntity: Enchere::class, mappedBy: 'id_agriculteur')]
    private Collection $encheres_agriculteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // Clear temporary sensitive data
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }
}
