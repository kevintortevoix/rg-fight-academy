<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    // -------------------------------------------------------------------------
    // Propriétés
    // -------------------------------------------------------------------------

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?float $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    /** @var Collection<int, PanierProduit> */
    #[ORM\OneToMany(targetEntity: PanierProduit::class, mappedBy: 'produit')]
    private Collection $panierProduits;

    /** @var Collection<int, CommandeProduit> */
    #[ORM\OneToMany(targetEntity: CommandeProduit::class, mappedBy: 'produit')]
    private Collection $commandeProduits;

    /**
     * @var Collection<int, ProduitTaille>
     */
    #[ORM\OneToMany(targetEntity: ProduitTaille::class, mappedBy: 'produit')]
    private Collection $produitTailles;

    // -------------------------------------------------------------------------
    // Constructeur
    // -------------------------------------------------------------------------

    public function __construct()
    {
        $this->panierProduits   = new ArrayCollection();
        $this->commandeProduits = new ArrayCollection();
        $this->produitTailles = new ArrayCollection();
    }

    // -------------------------------------------------------------------------
    // Getters / Setters
    // -------------------------------------------------------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }


    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    // -------------------------------------------------------------------------
    // Relation : PanierProduit
    // -------------------------------------------------------------------------

    /** @return Collection<int, PanierProduit> */
    public function getPanierProduits(): Collection
    {
        return $this->panierProduits;
    }

    public function addPanierProduit(PanierProduit $panierProduit): static
    {
        if (!$this->panierProduits->contains($panierProduit)) {
            $this->panierProduits->add($panierProduit);
            $panierProduit->setProduit($this);
        }

        return $this;
    }

    public function removePanierProduit(PanierProduit $panierProduit): static
    {
        if ($this->panierProduits->removeElement($panierProduit)) {
            if ($panierProduit->getProduit() === $this) {
                $panierProduit->setProduit(null);
            }
        }

        return $this;
    }

    // -------------------------------------------------------------------------
    // Relation : CommandeProduit
    // -------------------------------------------------------------------------

    /** @return Collection<int, CommandeProduit> */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): static
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits->add($commandeProduit);
            $commandeProduit->setProduit($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): static
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            if ($commandeProduit->getProduit() === $this) {
                $commandeProduit->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProduitTaille>
     */
    public function getProduitTailles(): Collection
    {
        return $this->produitTailles;
    }

    public function addProduitTaille(ProduitTaille $produitTaille): static
    {
        if (!$this->produitTailles->contains($produitTaille)) {
            $this->produitTailles->add($produitTaille);
            $produitTaille->setProduit($this);
        }

        return $this;
    }

    public function removeProduitTaille(ProduitTaille $produitTaille): static
    {
        if ($this->produitTailles->removeElement($produitTaille)) {
            // set the owning side to null (unless already changed)
            if ($produitTaille->getProduit() === $this) {
                $produitTaille->setProduit(null);
            }
        }

        return $this;
    }

    public function __toString(): string
{
    return $this->nom ?? '';
}
}