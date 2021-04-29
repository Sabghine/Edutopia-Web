<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Question
 *
 * @ORM\Table(name="question", indexes={@ORM\Index(name="id_Exam", columns={"id_Exam"})})
 * @ORM\Entity
 */
class Question
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
     * @ORM\Column(name="question", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Description field is required")
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="proposition1", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Description field is required")
     */
    private $proposition1;

    /**
     * @var string
     *
     * @ORM\Column(name="proposition2", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Description field is required")
     */
    private $proposition2;

    /**
     * @var string
     *
     * @ORM\Column(name="proposition3", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Description field is required")
     */
    private $proposition3;

    /**
     * @var string
     *
     * @ORM\Column(name="proposition4", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Description field is required")
     */
    private $proposition4;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="archived_Date", type="date", nullable=true)
     */
    private $archivedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="bonnereponse", type="string", length=255, nullable=false)
     */
    private $bonnereponse;

    /**
     * @var \Exam
     *
     * @ORM\ManyToOne(targetEntity="Exam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_Exam", referencedColumnName="id_Exam")
     * })
     */
    private $idExam;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getProposition1(): ?string
    {
        return $this->proposition1;
    }

    public function setProposition1(string $proposition1): self
    {
        $this->proposition1 = $proposition1;

        return $this;
    }

    public function getProposition2(): ?string
    {
        return $this->proposition2;
    }

    public function setProposition2(string $proposition2): self
    {
        $this->proposition2 = $proposition2;

        return $this;
    }

    public function getProposition3(): ?string
    {
        return $this->proposition3;
    }

    public function setProposition3(string $proposition3): self
    {
        $this->proposition3 = $proposition3;

        return $this;
    }

    public function getProposition4(): ?string
    {
        return $this->proposition4;
    }

    public function setProposition4(string $proposition4): self
    {
        $this->proposition4 = $proposition4;

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

    public function getBonnereponse(): ?string
    {
        return $this->bonnereponse;
    }

    public function setBonnereponse(string $bonnereponse): self
    {
        $this->bonnereponse = $bonnereponse;

        return $this;
    }

    public function getIdExam(): ?Exam
    {
        return $this->idExam;
    }

    public function setIdExam(?Exam $idExam): self
    {
        $this->idExam = $idExam;

        return $this;
    }


}
