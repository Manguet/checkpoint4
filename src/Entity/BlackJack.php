<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlackJackRepository")
 */
class BlackJack
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $played_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $toPay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amount;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Profil", mappedBy="blackjack")
     */
    private $profils;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Card", mappedBy="blackjack")
     */
    private $cards;

    /**
     * @ORM\Column(type="integer")
     */
    private $playerScore;

    /**
     * @ORM\Column(type="integer")
     */
    private $bankScore;

    public function __construct()
    {
        $this->profils = new ArrayCollection();
        $this->cards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayedAt(): ?\DateTimeInterface
    {
        return $this->played_at;
    }

    public function setPlayedAt(?\DateTimeInterface $played_at): self
    {
        $this->played_at = $played_at;

        return $this;
    }

    public function getToPay(): ?int
    {
        return $this->toPay;
    }

    public function setToPay(int $toPay): self
    {
        $this->toPay = $toPay;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection|Profil[]
     */
    public function getProfils(): Collection
    {
        return $this->profils;
    }

    public function addProfil(Profil $profil): self
    {
        if (!$this->profils->contains($profil)) {
            $this->profils[] = $profil;
            $profil->addBlackjack($this);
        }

        return $this;
    }

    public function removeProfil(Profil $profil): self
    {
        if ($this->profils->contains($profil)) {
            $this->profils->removeElement($profil);
            $profil->removeBlackjack($this);
        }

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setBlackjack($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->contains($card)) {
            $this->cards->removeElement($card);
            // set the owning side to null (unless already changed)
            if ($card->getBlackjack() === $this) {
                $card->setBlackjack(null);
            }
        }

        return $this;
    }

    public function getPlayerScore(): ?int
    {
        return $this->playerScore;
    }

    public function setPlayerScore(int $playerScore): self
    {
        $this->playerScore = $playerScore;

        return $this;
    }

    public function getBankScore(): ?int
    {
        return $this->bankScore;
    }

    public function setBankScore(int $bankScore): self
    {
        $this->bankScore = $bankScore;

        return $this;
    }
}
