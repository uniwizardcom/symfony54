<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $canceled = false;

    /**
     * @ORM\Column(type="smallint")
     */
    private $sended = false;

    /**
     * @ORM\OneToMany(targetEntity=OrderItems::class, mappedBy="order", cascade={"remove"})
     */
    private $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCanceled(): ?int
    {
        return $this->canceled;
    }

    public function setCanceled(int $canceled): self
    {
        $this->canceled = $canceled;

        return $this;
    }

    public function getSended(): ?int
    {
        return $this->sended;
    }

    public function setSended(int $sended): self
    {
        $this->sended = $sended;

        return $this;
    }

    /**
     * @return Collection<int, OrderItems>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function removeOrderItem(OrderItems $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }

        return $this;
    }

    public function countOfItems(): int
    {
        return $this->orderItems->count();
    }

    public function countOfProducts(): int
    {
        $count = 0;

        /** @var OrderItems $orderItem */
        foreach($this->orderItems as $orderItem) {
            $count += $orderItem->getProductsCount();
        }

        return $count;
    }
}
