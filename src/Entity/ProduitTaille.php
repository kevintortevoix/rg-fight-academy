<?php

namespace App\Entity;

use App\Repository\ProduitTailleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitTailleRepository::class)]
class ProduitTaille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $taille = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'produitTailles')]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaille(): ?string
    {
        return $this->taille;
    }

    public function setTaille(string $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}
