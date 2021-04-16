<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Exam
 *
 * @ORM\Table(name="exam", indexes={@ORM\Index(name="id_subject", columns={"id_subject"}), @ORM\Index(name="created_by", columns={"created_by"})})
 * @ORM\Entity(repositoryClass="App\Repository\ExamRepository")
 */
class Exam
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_Exam", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idExam;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Description field is required")
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finish_date", type="date", nullable=false)
     */
    private $finishDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_Date", type="date", nullable=false)
     */
    private $createdDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="archived_Date", type="date", nullable=true)
     */
    private $archivedDate;

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
     * @var \Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_subject", referencedColumnName="id")
     * })
     */
    private $idSubject;

    public function getIdExam(): ?int
    {
        return $this->idExam;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getFinishDate(): ?\DateTimeInterface
    {
        return $this->finishDate;
    }

    public function setFinishDate(\DateTimeInterface $finishDate): self
    {
        $this->finishDate = $finishDate;

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

    public function getArchivedDate(): ?\DateTimeInterface
    {
        return $this->archivedDate;
    }

    public function setArchivedDate(?\DateTimeInterface $archivedDate): self
    {
        $this->archivedDate = $archivedDate;

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

    public function getIdSubject(): ?Subject
    {
        return $this->idSubject;
    }

    public function setIdSubject(?Subject $idSubject): self
    {
        $this->idSubject = $idSubject;

        return $this;
    }

    public function __construct()
    {
        $this->createdDate = new \DateTime();
    }
}
