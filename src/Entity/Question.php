<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $quest = null;

    #[ORM\Column(length: 255)]
    private ?string $choice1 = null;

    #[ORM\Column(length: 255)]
    private ?string $choice2 = null;

    #[ORM\Column(length: 255)]
    private ?string $choice3 = null;

    #[ORM\Column(length: 255)]
    private ?string $respone = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?Test $test = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuest(): ?string
    {
        return $this->quest;
    }

    public function setQuest(string $quest): self
    {
        $this->quest = $quest;

        return $this;
    }

    public function getChoice1(): ?string
    {
        return $this->choice1;
    }

    public function setChoice1(string $choice1): self
    {
        $this->choice1 = $choice1;

        return $this;
    }

    public function getChoice2(): ?string
    {
        return $this->choice2;
    }

    public function setChoice2(string $choice2): self
    {
        $this->choice2 = $choice2;

        return $this;
    }

    public function getChoice3(): ?string
    {
        return $this->choice3;
    }

    public function setChoice3(string $choice3): self
    {
        $this->choice3 = $choice3;

        return $this;
    }

    public function getRespone(): ?string
    {
        return $this->respone;
    }

    public function setRespone(string $respone): self
    {
        $this->respone = $respone;

        return $this;
    }

    public function getTest(): ?Test
    {
        return $this->test;
    }

    public function setTest(?Test $test): self
    {
        $this->test = $test;

        return $this;
    }
}
