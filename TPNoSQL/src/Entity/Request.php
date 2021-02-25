<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequestRepository::class)
 */
class Request
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $result;

    /**
     * @ORM\Column(type="time")
     */
    private $executionTime;

    /**
     * @ORM\ManyToOne(targetEntity=Database::class, inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $databaseName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getExecutionTime(): ?\DateTimeInterface
    {
        return $this->executionTime;
    }

    public function setExecutionTime(\DateTimeInterface $executionTime): self
    {
        $this->executionTime = $executionTime;

        return $this;
    }

    public function getDatabaseName(): ?Database
    {
        return $this->databaseName;
    }

    public function setDatabaseName(?Database $databaseName): self
    {
        $this->databaseName = $databaseName;

        return $this;
    }
}
