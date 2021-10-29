<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomRepository::class)
 */
class Room
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nb_place;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="rooms")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id", nullable=true)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="rooms")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id", nullable=true)
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="room")
     */
    private $rooms;

    /**
     * @ORM\OneToMany(targetEntity=UserRoom::class, mappedBy="room")
     */
    private $user;

    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->user = new ArrayCollection();
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

    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    public function setNbPlace(?int $nb_place): self
    {
        $this->nb_place = $nb_place;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getRoom(): ?self
    {
        return $this->room;
    }

    public function setRoom(?self $room): ?self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    public function addRoom(?self $room): ?self
    {
        if (!$this->rooms->contains($room) && $room !== NULL) {
            $this->rooms[] = $room;
            $room->setRoom($this);
        }

        return $this;
    }

    public function removeRoom(self $room): self
    {
        if ($this->rooms->removeElement($room)) {
            // set the owning side to null (unless already changed)
            if ($room->getRoom() === $this) {
                $room->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserRoom[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(UserRoom $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setRoom($this);
        }

        return $this;
    }

    public function removeUser(UserRoom $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getRoom() === $this) {
                $user->setRoom(null);
            }
        }

        return $this;
    }

}
