<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PokemonRepository")
 */
class Pokemon
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
	
    /**
     * @ORM\Column(type="integer")
     */
	private $idPokemon;
	
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", options={"default": "1"})
     */
    private $difficulty = 1;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Pokedex", mappedBy="pokemon", orphanRemoval=true)
     */
    private $pokedex;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->pokedex = new ArrayCollection();
    }

	public function __toString()
         	{
         		return $this->getName();
         	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getIdPokemon(): ?int
    {
        return $this->idPokemon;
    }

    public function setIdPokemon(int $idPokemon): self
    {
        $this->idPokemon = $idPokemon;

        return $this;
    }

    /**
     * @return Collection|Pokedex[]
     */
    public function getPokedex(): Collection
    {
        return $this->pokedex;
    }

    public function addPokedex(Pokedex $pokedex): self
    {
        if (!$this->pokedex->contains($pokedex)) {
            $this->pokedex[] = $pokedex;
            $pokedex->setPokemon($this);
        }

        return $this;
    }

    public function removePokedex(Pokedex $pokedex): self
    {
        if ($this->pokedex->contains($pokedex)) {
            $this->pokedex->removeElement($pokedex);
            // set the owning side to null (unless already changed)
            if ($pokedex->getPokemon() === $this) {
                $pokedex->setPokemon(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
