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

    #[ORM\Column]
    private ?int $idArtist = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $idGenre = null;

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
     * @return int|null
     */
    public function getIdArtist(): ?int
    {
        return $this->idArtist;
    }

    /**
     * @param int $idArtist
     *
     * @return $this
     */
    public function setIdArtist(int $idArtist): self
    {
        $this->idArtist = $idArtist;

        return $this;
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
     * @return int|null
     */
    public function getIdGenre(): ?int
    {
        return $this->idGenre;
    }

    /**
     * @param int $idGenre
     *
     * @return $this
     */
    public function setIdGenre(int $idGenre): self
    {
        $this->idGenre = $idGenre;

        return $this;
    }
}
