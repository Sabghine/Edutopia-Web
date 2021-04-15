<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Classe
 *
 * @ORM\Table(name="classe", indexes={@ORM\Index(name="updated_by_classe", columns={"last_update_by"}), @ORM\Index(name="created_by_classe", columns={"created_by"}), @ORM\Index(name="archived_by_classe", columns={"archived_by"})})
 * @ORM\Entity(repositoryClass="App\Repository\ClasseRepository")
 */
class Classe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="classe_size", type="integer", nullable=false)
     */
    private $classeSize;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_date", type="date", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_update_date", type="date", nullable=true)
     */
    private $lastUpdateDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="archived_date", type="date", nullable=true)
     */
    private $archivedDate;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="archived_by", referencedColumnName="id")
     * })
     */
    private $archivedBy;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="last_update_by", referencedColumnName="id")
     * })
     */
    private $lastUpdateBy;

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

    public function getClasseSize(): ?int
    {
        return $this->classeSize;
    }

    public function setClasseSize(int $classeSize): self
    {
        $this->classeSize = $classeSize;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(?\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->lastUpdateDate;
    }

    public function setLastUpdateDate(?\DateTimeInterface $lastUpdateDate): self
    {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
    }

    public function getArchivedDate(): ?\DateTimeInterface
    {
        return $this->archivedDate;
    }

    public function setArchivedDate(?\DateTimeInterface $archivedDate): self
    {
        $this->archivedDate = $archivedDate;

        return $this;
    }

    public function getArchivedBy(): ?User
    {
        return $this->archivedBy;
    }

    public function setArchivedBy(?User $archivedBy): self
    {
        $this->archivedBy = $archivedBy;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getLastUpdateBy(): ?User
    {
        return $this->lastUpdateBy;
    }

    public function setLastUpdateBy(?User $lastUpdateBy): self
    {
        $this->lastUpdateBy = $lastUpdateBy;

        return $this;
    }


}
