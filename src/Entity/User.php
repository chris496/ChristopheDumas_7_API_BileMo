<?php

namespace App\Entity;

// use Assert\NotBlank;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Hateoas\Configuration\Annotation\Relation;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @Relation(
 *      "allUsers",
 *      href = @Hateoas\Route(
 *          "users",
 *          parameters = { "idClient" = "expr(object.getClient().getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUsers", excludeIf = "expr(not is_granted('ROLE_CLIENT'))", excludeIf = "expr(object.getClient() === null)")
 * )
 * @Relation(
 *      "oneUser",
 *      href = @Hateoas\Route(
 *          "detailUser",
 *          parameters = { "idClient" = "expr(object.getClient().getId())", "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUsers", excludeIf = "expr(not is_granted('ROLE_CLIENT'))")
 * )
 * @Relation(
 *      "deleteUser",
 *      href = @Hateoas\Route(
 *          "deleteUser",
 *          parameters = { "idClient" = "expr(object.getClient().getId())", "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUsers", excludeIf = "expr(not is_granted('ROLE_CLIENT'))")
 * )
 * * @Relation(
 *      "postUser",
 *      href = @Hateoas\Route(
 *          "createUser",
 *          parameters = { "idClient" = "expr(object.getClient().getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUsers", excludeIf = "expr(not is_granted('ROLE_CLIENT'))")
 * )
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getUsers'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getUsers'])]
    #[NotBlank(message: 'le prénom est obligatoire')]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getUsers'])]
    #[NotBlank(message: 'le nom est obligatoire')]
    private ?string $lastname = null;

    #[ORM\Column(length: 50, unique : true)]
    #[Groups(['getUsers'])]
    #[NotBlank(message: "l'adresse mail est obligatoire")]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    #[Groups(['getUsers'])]
    #[NotBlank(message: 'le mot de passe est obligatoire')]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(['getUsers'])]
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
