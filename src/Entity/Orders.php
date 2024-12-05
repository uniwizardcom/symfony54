<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
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
}
