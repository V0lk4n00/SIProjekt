<?php
/**
 * Genre entity.
 */
namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Genre.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $genreName = null;

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
    public function getGenreName(): ?string
    {
        return $this->genreName;
    }

    /**
     * @param string $genreName
     *
     * @return $this
     */
    public function setGenreName(string $genreName): self
    {
        $this->genreName = $genreName;

        return $this;
    }
}
