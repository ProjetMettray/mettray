<?php

namespace App\Entity;

use App\Repository\RoomAssociationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoomAssociationRepository::class)
 */
class RoomAssociation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Association::class, inversedBy="roomAssociations")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $association;

    /**
     * @ORM\ManyToOne(targetEntity=Room::class, inversedBy="roomAssociations")
     * @ORM\JoinColumn(onDelete="CASCADE") 
     */
    private $room;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAssociation(): ?Association
    {
        return $this->association;
    }

    public function setAssociation(?Association $association): self
    {
        $this->association = $association;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }
}
