<?php
/**
 * Author entity.
 */

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

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
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Name.
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $name = null;

    /**
     * Surname.
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $surname = null;

    /**
     * Alias.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $alias = null;

    /**
     * Slug.
     */
    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 64)]
    #[Gedmo\Slug(fields: ['name', 'surname', 'alias'])]
    private ?string $slug = null;

    /**
     * Primary key.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null Result
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name Name
     *
     * @return $this Result
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null Result
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string|null $surname Surname
     *
     * @return $this Result
     */
    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string|null Result
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param string $alias Alias
     *
     * @return $this Result
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @return string|null Result
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug Slug
     *
     * @return $this Result
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
