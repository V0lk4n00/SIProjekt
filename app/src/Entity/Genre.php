<?php
/**
 * Genre entity.
 */

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Genre.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: GenreRepository::class)]
#[ORM\UniqueConstraint(name: 'uq_genre_genreName', columns: ['genre_Name'])]
#[UniqueEntity(fields: ['genreName'])]
class Genre
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Title.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $genreName = null;

    /**
     * Slug.
     */
    #[ORM\Column(type: 'string', length: 64)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 64)]
    #[Gedmo\Slug(fields: ['genreName'])]
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
    public function getGenreName(): ?string
    {
        return $this->genreName;
    }

    /**
     * @param string $genreName Genre name
     *
     * @return $this Result
     */
    public function setGenreName(string $genreName): self
    {
        $this->genreName = $genreName;

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
