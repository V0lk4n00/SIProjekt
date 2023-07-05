<?php

/**
 * Reservation entity.
 */

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reservation class.
 */
#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 64)]
    private ?string $nick = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    private ?string $content = null;

    #[ORM\ManyToOne(
        targetEntity: Record::class,
        fetch: 'EXTRA_LAZY',
    )]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(Record::class)]
    private ?Record $record = null;

    /**
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null Result
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email Email
     *
     * @return $this Result
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null Result
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * @param string $nick Nick
     *
     * @return $this Result
     */
    public function setNick(string $nick): self
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * @return string|null Result
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content Content
     *
     * @return $this Result
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Record|null Result
     */
    public function getRecord(): ?Record
    {
        return $this->record;
    }

    /**
     * @param Record|null $record Record setter
     *
     * @return $this Result
     */
    public function setRecord(?Record $record): self
    {
        $this->record = $record;

        return $this;
    }
}
