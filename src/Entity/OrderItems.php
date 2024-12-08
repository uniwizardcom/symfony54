<?php

namespace App\Entity;

use App\Repository\OrderItemsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderItemsRepository::class)
 */
class OrderItems
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $products_count = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Orders::class, inversedBy="orderItems", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @ORM\Column(type="integer")
     */
    private $product_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductsCount(): ?int
    {
        return $this->products_count;
    }

    public function setProductsCount(int $productsCount): self
    {
        $this->products_count = $productsCount;

        return $this;
    }

    public function increaseProductsCount(int $inc = 1): self
    {
        $this->products_count += $inc;

        return $this;
    }

    public function decreaseProductsCount(int $dec = 1): self
    {
        return $this->increaseProductsCount(- $dec);
    }

    public function getOrder(): ?Orders
    {
        return $this->order;
    }

    public function setOrder(?Orders $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }
}
