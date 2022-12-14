<?php

namespace App\Entity;

use App\Repository\ProductOrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductOrderRepository::class)
 */
class ProductOrder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productOrders")
     */
    private $productID;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="productOrderID")
     */
    private $orderID;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductID(): ?Product
    {
        return $this->productID;
    }

    public function setProductID(?Product $productID): self
    {
        $this->productID = $productID;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->orderID;
    }

    public function setOrder(?Order $orderID): self
    {
        $this->orderID = $orderID;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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
}
