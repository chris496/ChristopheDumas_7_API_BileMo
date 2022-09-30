<?php

namespace App\Entity;

use Assert\NotBlank;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Hateoas\Configuration\Annotation\Relation;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @Relation(
 *      "allUsers",
 *      href = @Hateoas\Route(
 *          "users",
 *          parameters = { "idClient" = "expr(object.getClient().getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUsers", excludeIf = "expr(not is_granted('ROLE_ADMIN'))", excludeIf = "expr(object.getClient() === null)")
 * )
 *
 * @Relation(
 *      "oneUser",
 *      href = @Hateoas\Route(
 *          "detailUser",
 *          parameters = { "idClient" = "expr(object.getClient().getId())", "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUsers", excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 * )
 *
 * @Relation(
 *      "deleteUser",
 *      href = @Hateoas\Route(
 *          "deleteUser",
 *          parameters = { "idClient" = "expr(object.getClient().getId())", "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUsers", excludeIf = "expr(not is_granted('ROLE_ADMIN'))")
 * )
 *
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getUsers"])]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getUsers"])]
    private ?string $lastname = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getUsers"])]
    #[NotBlank(message: "l'adresse mail est obligatoire")]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getUsers"])]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}
