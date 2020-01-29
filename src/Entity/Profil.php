<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfilRepository")
 */
class Profil
{
    const BASE_AMOUNT = 100;

    const DEFAULT_TITLE = 'Mon Profil';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title = self::DEFAULT_TITLE;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_amount = self::BASE_AMOUNT;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Roulette", inversedBy="profils")
     */
    private $roulette;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\BlackJack", inversedBy="profils")
     */
    private $blackjack;

    public function __construct()
    {
        $this->roulette = new ArrayCollection();
        $this->blackjack = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTotalAmount(): ?int
    {
        return $this->total_amount;
    }

    public function setTotalAmount(int $total_amount): self
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    /**
     * @return Collection|Roulette[]
     */
    public function getRoulette(): Collection
    {
        return $this->roulette;
    }

    public function addRoulette(Roulette $roulette): self
    {
        if (!$this->roulette->contains($roulette)) {
            $this->roulette[] = $roulette;
        }

        return $this;
    }

    public function removeRoulette(Roulette $roulette): self
    {
        if ($this->roulette->contains($roulette)) {
            $this->roulette->removeElement($roulette);
        }

        return $this;
    }

    /**
     * @return Collection|BlackJack[]
     */
    public function getBlackjack(): Collection
    {
        return $this->blackjack;
    }

    public function addBlackjack(BlackJack $blackjack): self
    {
        if (!$this->blackjack->contains($blackjack)) {
            $this->blackjack[] = $blackjack;
        }

        return $this;
    }

    public function removeBlackjack(BlackJack $blackjack): self
    {
        if ($this->blackjack->contains($blackjack)) {
            $this->blackjack->removeElement($blackjack);
        }

        return $this;
    }
}
