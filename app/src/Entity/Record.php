<?php
/**
 * Record entity.
 */

namespace App\Entity;

use App\Repository\RecordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $title = null;

    /**
     * Genre.
     */
    #[ORM\ManyToOne(
        targetEntity: Genre::class,
        fetch: 'EXTRA_LAZY',
    )]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(Genre::class)]
    private ?Genre $genre = null;

    #[ORM\ManyToOne(
        targetEntity: Author::class,
        fetch: 'EXTRA_LAZY',
    )]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(Author::class)]
    private ?Author $author = null;

    /**
     * Rental.
     */
    #[ORM\ManyToOne(
        targetEntity: User::class,
        fetch: 'EXTRA_LAZY'
    )]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private ?User $rental;

    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(0)]
    private ?int $inStock = null;

    /**
     * Primary key.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    /**
     * @return $this
     */
    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * @return $this
     */
    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getRental(): ?User
    {
        return $this->rental;
    }

    /**
     * @return $this
     */
    public function setRental(?User $rental): self
    {
        $this->rental = $rental;

        return $this;
    }

    public function getInStock(): ?int
    {
        return $this->inStock;
    }

    /**
     * @return $this
     */
    public function setInStock(int $inStock): self
    {
        $this->inStock = $inStock;

        return $this;
    }
}
