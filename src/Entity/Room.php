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
    private $nbPlace;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="rooms")
     */
    private $room_has_user;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="room_id")
     */
    private $roomParent;

    /**
     * @ORM\OneToMany(targetEntity=Room::class, mappedBy="room")
     */
    private $roomId;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="room_id")
     */
    private $events;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="rooms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locationId;

    public function __construct()
    {
        $this->room_has_user = new ArrayCollection();
        $this->room_id = new ArrayCollection();
        $this->events = new ArrayCollection();
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
        return $this->nbPlace;
    }

    public function setNbPlace(int $nbPlace): self
    {
        $this->nbPlace = $nbPlace;

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
        return $this->roomParent;
    }

    public function setRoom(?self $roomParent): self
    {
        $this->roomParent = $roomParent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getRoomId(): Collection
    {
        return $this->roomId;
    }

    public function addRoomId(self $roomId): self
    {
        if (!$this->roomId->contains($roomId)) {
            $this->roomId[] = $roomId;
            $roomId->setRoom($this);
        }

        return $this;
    }

    public function removeRoomId(self $roomId): self
    {
        if ($this->roomId->removeElement($roomId)) {
            // set the owning side to null (unless already changed)
            if ($roomId->getRoom() === $this) {
                $roomId->setRoom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setRoomId($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getRoomId() === $this) {
                $event->setRoomId(null);
            }
        }

        return $this;
    }

    public function getLocationId(): ?Location
    {
        return $this->locationId;
    }

    public function setLocationId(?Location $locationId): self
    {
        $this->locationId = $locationId;

        return $this;
    }

    public function getRoomParent(): ?Room
    {
        return $this->roomParent;
    }

    public function setRoomParent(?Room $roomParent): self
    {
        $this->roomParent = $roomParent;

        return $this;
    }
}
