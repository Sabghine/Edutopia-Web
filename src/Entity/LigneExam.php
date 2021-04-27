<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LigneExam
 *
 * @ORM\Table(name="ligne_exam", indexes={@ORM\Index(name="idexam", columns={"idexam"}), @ORM\Index(name="iduser", columns={"iduser"})})
 * @ORM\Entity(repositoryClass="App\Repository\LigneExamRepository")
 */
class LigneExam
{
    /**
     * @var int
     *
     * @ORM\Column(name="idligne", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idligne;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="float", precision=10, scale=0, nullable=false)
     */
    private $note;

    /**
     *

     * @ORM\OneToOne(targetEntity="Exam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idexam", referencedColumnName="id_Exam")
     * })
     */
    private $idexam;

    /**
     * @var \User
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;

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

    public function getIdexam()
    {
        return $this->idexam;
    }

    public function setIdexam( $idexam): self
    {
        $this->idexam = $idexam;

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


}
