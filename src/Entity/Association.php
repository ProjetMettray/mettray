<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AssociationRepository::class)
 */
class Association
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="association")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $bookings;

    /**
     * @ORM\OneToMany(targetEntity=AssociationUser::class, mappedBy="association")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $associationUsers;

    /**
     * @ORM\ManyToMany(targetEntity=Room::class, inversedBy="associations")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $rooms;

    public function __construct()
    {
        $this->user_has_association = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->associationUsers = new ArrayCollection();
        $this->roomAssociations = new ArrayCollection();
        $this->associationRooms = new ArrayCollection();
        $this->rooms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function __toString()
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setAssociation($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getAssociation() === $this) {
                $booking->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AssociationUser[]
     */
    public function getAssociationUsers(): Collection
    {
        return $this->associationUsers;
    }

    public function addAssociationUser(AssociationUser $associationUser): self
    {
        if (!$this->associationUsers->contains($associationUser)) {
            $this->associationUsers[] = $associationUser;
            $associationUser->setAssociation($this);
        }

        return $this;
    }

    public function removeAssociationUser(AssociationUser $associationUser): self
    {
        if ($this->associationUsers->removeElement($associationUser)) {
            // set the owning side to null (unless already changed)
            if ($associationUser->getAssociation() === $this) {
                $associationUser->setAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
        }

        return $this;
    }

    public function removeRoom(Room $room): self
    {
        $this->rooms->removeElement($room);

        return $this;
    }
}
