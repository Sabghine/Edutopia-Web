<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneExam
 *
 * @ORM\Table(name="ligne_exam", indexes={@ORM\Index(name="iduser", columns={"iduser"}), @ORM\Index(name="idexam", columns={"idexam"})})
 * @ORM\Entity
 */
class LigneExam
{
    /**
     * @var int
     *
     * @ORM\Column(name="idligne", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idligne;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float", precision=10, scale=0, nullable=false)
     */
    private $note;

    /**
     * @var \User
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;

    /**
     * @var \Exam
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Exam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idexam", referencedColumnName="id_Exam")
     * })
     */
    private $idexam;

    public function getIdligne(): ?int
    {
        return $this->idligne;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): self
    {
        $this->iduser = $iduser;

        return $this;
    }

    public function getIdexam(): ?Exam
    {
        return $this->idexam;
    }

    public function setIdexam(?Exam $idexam): self
    {
        $this->idexam = $idexam;

        return $this;
    }


}
