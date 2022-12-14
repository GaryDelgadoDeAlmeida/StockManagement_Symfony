<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Entity::class, inversedBy="products")
     */
    private $entityID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Caracteristic::class, mappedBy="productID")
     */
    private $caracteristics;

    /**
     * @ORM\OneToMany(targetEntity=Media::class, mappedBy="productID")
     */
    private $media;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock = 0;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $price = 0;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=ProductOrder::class, mappedBy="productID")
     */
    private $productOrders;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->caracteristics = new ArrayCollection();
        $this->productOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntity(): ?Entity
    {
        return $this->entityID;
    }

    public function setEntity(?Entity $entity): self
    {
        $this->entityID = $entity;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Caracteristic>
     */
    public function getCaracteristics(): Collection
    {
        return $this->caracteristics;
    }

    public function addCaracteristic(Caracteristic $caracteristic): self
    {
        if (!$this->caracteristics->contains($caracteristic)) {
            $this->caracteristics[] = $caracteristic;
            $caracteristic->setProductID($this);
        }

        return $this;
    }

    public function removeCaracteristic(Caracteristic $caracteristic): self
    {
        if ($this->caracteristics->removeElement($caracteristic)) {
            // set the owning side to null (unless already changed)
            if ($caracteristic->getProductID() === $this) {
                $caracteristic->setProductID(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedia(Media $media): self
    {
        if (!$this->media->contains($media)) {
            $this->media[] = $media;
            $media->setProductID($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->media->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getProductID() === $this) {
                $media->setProductID(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, ProductOrder>
     */
    public function getProductOrders(): Collection
    {
        return $this->productOrders;
    }

    public function addProductOrder(ProductOrder $productOrder): self
    {
        if (!$this->productOrders->contains($productOrder)) {
            $this->productOrders[] = $productOrder;
            $productOrder->setProductID($this);
        }

        return $this;
    }

    public function removeProductOrder(ProductOrder $productOrder): self
    {
        if ($this->productOrders->removeElement($productOrder)) {
            // set the owning side to null (unless already changed)
            if ($productOrder->getProductID() === $this) {
                $productOrder->setProductID(null);
            }
        }

        return $this;
    }
}
