<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $addressDelivery = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $user = null;

    // cascade: ['persist'] => permet de manipuler l'objet orderContent lorsqu'il est question de persister les données
    #[ORM\OneToMany(targetEntity: OrderContent::class, mappedBy: 'myOrder', cascade: ['persist'])]
    private Collection $orderContents;

    /*
     * état de la commande :
     * 1: en attente de paiement
     * 2: paiement validé
     * 3: expédiée
     */
    #[ORM\Column]
    private ?int $state = null;

    public function __construct()
    {
        $this->orderContents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAddressDelivery(): ?string
    {
        return $this->addressDelivery;
    }

    public function setAddressDelivery(string $addressDelivery): static
    {
        $this->addressDelivery = $addressDelivery;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, OrderContent>
     */
    public function getOrderContents(): Collection
    {
        return $this->orderContents;
    }

    public function addOrderContent(OrderContent $orderContent): static
    {
        if (!$this->orderContents->contains($orderContent)) {
            $this->orderContents->add($orderContent);
            $orderContent->setMyOrder($this);
        }

        return $this;
    }

    public function removeOrderContent(OrderContent $orderContent): static
    {
        if ($this->orderContents->removeElement($orderContent)) {
            // set the owning side to null (unless already changed)
            if ($orderContent->getMyOrder() === $this) {
                $orderContent->setMyOrder(null);
            }
        }

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): static
    {
        $this->state = $state;

        return $this;
    }
}
