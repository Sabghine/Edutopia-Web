<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoStudying
 *
 * @ORM\Table(name="co_studying", indexes={@ORM\Index(name="archived_by", columns={"archived_by"}), @ORM\Index(name="IDX_5B8A12508CDE5729", columns={"type"}), @ORM\Index(name="id_student", columns={"id_student"}), @ORM\Index(name="last_updated_by", columns={"last_updated_by"})})
 * @ORM\Entity
 */
class CoStudying
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
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=20, nullable=false)
     */
    private $niveau;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_date", type="date", nullable=true)
     */
    private $createdDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_updated_date", type="date", nullable=true)
     */
    private $lastUpdatedDate;

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
     *   @ORM\JoinColumn(name="last_updated_by", referencedColumnName="id")
     * })
     */
    private $lastUpdatedBy;

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
     * @var \Costudyingtype
     *
     * @ORM\ManyToOne(targetEntity="Costudyingtype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_student", referencedColumnName="id")
     * })
     */
    private $idStudent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedDate(): ?\DateTime
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime|null $createdDate
     */
    public function setCreatedDate(?\DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastUpdatedDate(): ?\DateTime
    {
        return $this->lastUpdatedDate;
    }

    /**
     * @param \DateTime|null $lastUpdatedDate
     */
    public function setLastUpdatedDate(?\DateTime $lastUpdatedDate): void
    {
        $this->lastUpdatedDate = $lastUpdatedDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getArchivedDate(): ?\DateTime
    {
        return $this->archivedDate;
    }

    /**
     * @param \DateTime|null $archivedDate
     */
    public function setArchivedDate(?\DateTime $archivedDate): void
    {
        $this->archivedDate = $archivedDate;
    }

    public function getLastUpdatedBy(): ?User
    {
        return $this->lastUpdatedBy;
    }

    public function setLastUpdatedBy(?User $lastUpdatedBy): self
    {
        $this->lastUpdatedBy = $lastUpdatedBy;

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

    public function getType(): ?Costudyingtype
    {
        return $this->type;
    }

    public function setType(?Costudyingtype $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIdStudent(): ?User
    {
        return $this->idStudent;
    }

    public function setIdStudent(?User $idStudent): self
    {
        $this->idStudent = $idStudent;

        return $this;
    }

    public function __toString()
    {
        return $this->description;
    }



}
