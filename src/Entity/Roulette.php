<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RouletteRepository")
 */
class Roulette
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
    private $to_pay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amount;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Profil", mappedBy="roulette")
     */
    private $profils;

    public function __construct()
    {
        $this->profils = new ArrayCollection();
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
        return $this->to_pay;
    }

    public function setToPay(int $to_pay): self
    {
        $this->to_pay = $to_pay;

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
            $profil->addRoulette($this);
        }

        return $this;
    }

    public function removeProfil(Profil $profil): self
    {
        if ($this->profils->contains($profil)) {
            $this->profils->removeElement($profil);
            $profil->removeRoulette($this);
        }

        return $this;
    }
}
