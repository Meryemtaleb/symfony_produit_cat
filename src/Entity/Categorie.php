<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptif = null;


    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produits::class)]
    private Collection $prod;

    public function __construct()
    {
        $this->prod = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(?string $descriptif): static
    {
        $this->descriptif = $descriptif;

        return $this;
    }





    /**
     * @return Collection<int, Produits>
     */
    public function getProd(): Collection
    {
        return $this->prod;
    }

    public function addProd(Produits $prod): static
    {
        if (!$this->prod->contains($prod)) {
            $this->prod->add($prod);
            $prod->setCategorie($this);
        }

        return $this;
    }

    public function removeProd(Produits $prod): static
    {
        if ($this->prod->removeElement($prod)) {
            // set the owning side to null (unless already changed)
            if ($prod->getCategorie() === $this) {
                $prod->setCategorie(null);
            }
        }

        return $this;
    }
}
