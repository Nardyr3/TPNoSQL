<?php

namespace App\Entity;

use App\Repository\FriendRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FriendRepository::class)
 */
class Friend
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="friends")
     */
    private $idPerson;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="following")
     */
    private $idFollower;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPerson(): ?User
    {
        return $this->idPerson;
    }

    public function setIdPerson(?User $idPerson): self
    {
        $this->idPerson = $idPerson;

        return $this;
    }

    public function getIdFollower(): ?User
    {
        return $this->idFollower;
    }

    public function setIdFollower(?User $idFollower): self
    {
        $this->idFollower = $idFollower;

        return $this;
    }
}
