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
     * @ORM\Column(type="integer")
     */
    private $nb_place;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $location_id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="rooms")
     */
    private $room_has_user;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="room_id")
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="room")
     */
    private $room_id;

    public function __construct()
    {
        $this->room_has_user = new ArrayCollection();
        $this->room_id = new ArrayCollection();
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

    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    public function setNbPlace(int $nb_place): self
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

    public function getLocationId(): ?int
    {
        return $this->location_id;
    }

    public function setLocationId(?int $location_id): self
    {
        $this->location_id = $location_id;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRoomHasUser(): Collection
    {
        return $this->room_has_user;
    }

    public function addRoomHasUser(User $roomHasUser): self
    {
        if (!$this->room_has_user->contains($roomHasUser)) {
            $this->room_has_user[] = $roomHasUser;
        }

        return $this;
    }

    public function removeRoomHasUser(User $roomHasUser): self
    {
        $this->room_has_user->removeElement($roomHasUser);

        return $this;
    }

    public function getRoom(): ?self
    {
        return $this->room;
    }

    public function setRoom(?self $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getRoomId(): Collection
    {
        return $this->room_id;
    }

    public function addRoomId(self $roomId): self
    {
        if (!$this->room_id->contains($roomId)) {
            $this->room_id[] = $roomId;
            $roomId->setRoom($this);
        }

        return $this;
    }

    public function removeRoomId(self $roomId): self
    {
        if ($this->room_id->removeElement($roomId)) {
            // set the owning side to null (unless already changed)
            if ($roomId->getRoom() === $this) {
                $roomId->setRoom(null);
            }
        }

        return $this;
    }
}
