<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Subject
 *
 * @ORM\Table(name="subject", indexes={@ORM\Index(name="update_by", columns={"update_by"}), @ORM\Index(name="id_teacher", columns={"id_teacher"}), @ORM\Index(name="archived_by", columns={"archived_by"}), @ORM\Index(name="subject_ibfk_5", columns={"id_class"}), @ORM\Index(name="created_by", columns={"created_by"})})
 * @ORM\Entity(repositoryClass="App\Repository\SubjectRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Subject
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
     * @ORM\Column(name="id_Subject", type="string", length=30, nullable=false)
     * @Assert\NotBlank(message="Vous devez donner le nom de la matière")
     * @Assert\Length(min=3,minMessage="Doit contenir au min 3 caracteres")
     */
    private $idSubject;

    /**
     * @var string|null
     *
     * @ORM\Column(name="courses", type="string", length=50, nullable=true)
     */
    private $courses;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_date", type="date", nullable=true)
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
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */
    private $createdBy;

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
     *   @ORM\JoinColumn(name="id_teacher", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="Vous devez choisir un enseignant")
     */
    private $idTeacher;

    /**
     * @var \Classe
     *
     * @ORM\ManyToOne(targetEntity="Classe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_class", referencedColumnName="id")
     * })
     * @Assert\NotBlank(message="vous devez choisir une classe")
     */
    private $idClass;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSubject(): ?string
    {
        return $this->idSubject;
    }

    public function setIdSubject(string $idSubject): self
    {
        $this->idSubject = $idSubject;

        return $this;
    }

    public function getCourses(): ?string
    {
        return $this->courses;
    }

    public function setCourses(?string $courses): self
    {
        $this->courses = $courses;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

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

    public function getIdTeacher(): ?User
    {
        return $this->idTeacher;
    }

    public function setIdTeacher(?User $idTeacher): self
    {
        $this->idTeacher = $idTeacher;

        return $this;
    }

    public function getIdClass(): ?Classe
    {
        return $this->idClass;
    }

    public function setIdClass(?Classe $idClass): self
    {
        $this->idClass = $idClass;

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
