<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\PanierRepository;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $id_client = null;

    #[ORM\ManyToMany(targetEntity: ProduitStore::class)]
    private Collection $id_produit;

    #[ORM\Column(type: 'integer')]
    private int $quantite;

    #[ORM\Column(type: 'integer')]
    private int $total;

    public function __construct()
    {
        $this->id_produit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdClient(): ?User
    {
        return $this->id_client;
    }

    public function setIdClient(?User $id_client): self
    {
        $this->id_client = $id_client;
        return $this;
    }

    /**
     * @return Collection<int, ProduitStore>
     */
    public function getIdProduit(): Collection
    {
        return $this->id_produit;
    }

    public function addIdProduit(ProduitStore $produit): self
    {
        if (!$this->id_produit->contains($produit)) {
            $this->id_produit->add($produit);
        }
        return $this;
    }

    public function removeIdProduit(ProduitStore $produit): self
    {
        $this->id_produit->removeElement($produit);
        return $this;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;
        return $this;
    }



   public function getProduitsDetails(): array
   {
       return array_map(fn(ProduitStore $produit) => [
           'id' => $produit->getId(),
           'agriculteur_id' => $produit->getAgriculteurId()?->getId(),
           'categorie_id' => $produit->getCategorieId()?->getId(),
           'nom' => $produit->getNom(),
           'description' => $produit->getDescription(),
           'quantite' => $produit->getQuantite(),
           'prix' => $produit->getPrix(),
           'path_img' => $produit->getPathImg(),
           'agriculteur' => $produit->getAgriculteurId()?->getId(),
           'categorie' => $produit->getCategorieId()?->getNom(),
       ], $this->id_produit->toArray());
   }
   
}
