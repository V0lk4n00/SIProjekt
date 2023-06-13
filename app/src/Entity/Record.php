<?php
/**
 * Record entity.
 */
namespace App\Entity;

use App\Repository\RecordRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Record.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: RecordRepository::class)]
class Record
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * Genre.
     *
     * @var Genre|null
     */
    #[ORM\ManyToOne(
        targetEntity: Genre::class,
        fetch: 'EXTRA_LAZY',
    )]
    #[ORM\JoinColumn(nullable: false)]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(
        targetEntity: Author::class,
        fetch: 'EXTRA_LAZY',
    )]
    #[ORM\JoinColumn(nullable: false)]
    private ?Author $author = null;

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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Genre|null
     */
    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    /**
     * @param Genre|null $genre
     *
     * @return $this
     */
    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Author|null
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * @param Author|null $author
     *
     * @return $this
     */
    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
