<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
class History
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $createDate = null;

    #[ORM\Column(length: 255)]
    private ?string $question = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answer = null;

    #[ORM\ManyToOne(inversedBy: 'histories')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateDate(): ?\DateTime
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTime $createDate): static
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return html_entity_decode($this->answer);
    }

    public function setAnswer(?string $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
