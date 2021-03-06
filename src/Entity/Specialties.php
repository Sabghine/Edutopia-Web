<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Specialties
 *
 * @ORM\Table(name="specialties", indexes={@ORM\Index(name="update_by", columns={"update_by"}), @ORM\Index(name="idTeacher", columns={"idTeacher"}), @ORM\Index(name="created_by", columns={"created_by"}), @ORM\Index(name="IDX_410754B066B4BC6A", columns={"id_dep"}), @ORM\Index(name="archived_by", columns={"archived_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Specialties
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
     * @ORM\Column(name="name", type="string", length=30, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=30, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="specialty", type="string", length=3000, nullable=false)
     * @Assert\NotBlank(message="Vous devez donnez un nom de specialité")
     * @Assert\Length(min=2,minMessage="Doit contenir au min 3 caracteres")
     */
    private $specialty;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=3000, nullable=false)
     * @Assert\NotBlank(message="Vous devez donnez un niveau... par exemple:1ére,2éme")
     * @Assert\Length(min=4,minMessage="Au moins 4 caractères")
     */
    private $niveau;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="date", nullable=false)
     */
    private $createdDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="update_date", type="date", nullable=true)
     */
    private $updateDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="archived_date", type="date", nullable=true)
     */
    private $archivedDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=30, nullable=true)
     */
    private $status;

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
     *   @ORM\JoinColumn(name="update_by", referencedColumnName="id")
     * })
     */
    private $updateBy;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTeacher", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="Vous devez choisir un enseignant")
     */
    private $idteacher;

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
     * @var \Department
     *
     * @ORM\ManyToOne(targetEntity="Department")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_dep", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="vous devez choisir un département")
     */
    private $idDep;

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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(string $specialty): self
    {
        $this->specialty = $specialty;

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

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

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

    public function getUpdateBy(): ?User
    {
        return $this->updateBy;
    }

    public function setUpdateBy(?User $updateBy): self
    {
        $this->updateBy = $updateBy;

        return $this;
    }

    public function getIdteacher(): ?User
    {
        return $this->idteacher;
    }

    public function setIdteacher(?User $idteacher): self
    {
        $this->idteacher = $idteacher;

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

    public function getIdDep(): ?Department
    {
        return $this->idDep;
    }

    public function setIdDep(?Department $idDep): self

    {
        $this->idDep = $idDep;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function createdTimestamps(): void
    {
        $this->setCreatedDate(new \DateTime('now'));
    }
    /**
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdateDate(new \DateTime('now'));
    }


}
