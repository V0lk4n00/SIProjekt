<?php
/**
 * Author entity.
 */
namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Author.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Table(name: 'author')]
#[ORM\UniqueConstraint(name: 'uq_author_alias', columns: ['alias'])]
#[UniqueEntity(fields: ['alias'])]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    private ?string $alias = null;

    /**
     * Primary key.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string|null $surname
     *
     * @return $this
     */
    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     *
     * @return $this
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }
}
