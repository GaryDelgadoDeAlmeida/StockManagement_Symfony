<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="orders")
     */
    private $customerID;

    /**
     * @ORM\OneToMany(targetEntity=ProductOrder::class, mappedBy="orderID")
     */
    private $productOrderID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paid;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deliveredAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    public function __construct()
    {
        $this->productOrderID = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customerID;
    }

    public function setCustomer(?Customer $customerID): self
    {
        $this->customerID = $customerID;

        return $this;
    }

    /**
     * @return Collection<int, ProductOrder>
     */
    public function getProductOrderID(): Collection
    {
        return $this->productOrderID;
    }

    public function addProductOrderID(ProductOrder $productOrderID): self
    {
        if (!$this->productOrderID->contains($productOrderID)) {
            $this->productOrderID[] = $productOrderID;
            $productOrderID->setCommandOrder($this);
        }

        return $this;
    }

    public function removeProductOrderID(ProductOrder $productOrderID): self
    {
        if ($this->productOrderID->removeElement($productOrderID)) {
            // set the owning side to null (unless already changed)
            if ($productOrderID->getCommandOrder() === $this) {
                $productOrderID->setCommandOrder(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPaid()
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getDeliveredAt(): ?\DateTimeImmutable
    {
        return $this->deliveredAt;
    }

    public function setDeliveredAt(\DateTimeImmutable $deliveredAt): self
    {
        $this->deliveredAt = $deliveredAt;

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
