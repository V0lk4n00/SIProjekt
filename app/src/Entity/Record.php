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
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title Title
     *
     * @return $this Result
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Genre|null Result
     */
    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    /**
     * @param Genre|null $genre Genre name
     *
     * @return $this Result
     */
    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Author|null Result
     */
    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    /**
     * @param Author|null $author Author
     *
     * @return $this Result
     */
    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return User|null Result
     */
    public function getRental(): ?User
    {
        return $this->rental;
    }

    /**
     * @param User|null $rental Rental
     *
     * @return $this Result
     */
    public function setRental(?User $rental): self
    {
        $this->rental = $rental;

        return $this;
    }

    /**
     * @return int|null Result
     */
    public function getInStock(): ?int
    {
        return $this->inStock;
    }

    /**
     * @param int $inStock In stock
     *
     * @return $this Result
     */
    public function setInStock(int $inStock): self
    {
        $this->inStock = $inStock;

        return $this;
    }
}
