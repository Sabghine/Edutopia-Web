<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WorkDone
 *
 * @ORM\Table(name="work_done", indexes={@ORM\Index(name="archived_by", columns={"archived_by"}), @ORM\Index(name="uploaded_by", columns={"uploaded_by"}), @ORM\Index(name="id_activity", columns={"id_activity"}), @ORM\Index(name="last_updated_by", columns={"last_updated_by"})})
 * @ORM\Entity(repositoryClass="App\Repository\WorkDoneRepository")
 */
class WorkDone
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
     * @var string|null
     *
     * @ORM\Column(name="work_file", type="string", length=255, nullable=true)
     */
    private $workFile;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var int|null
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    private $score;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="uploaded_date", type="date", nullable=true)
     */
    private $uploadedDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_updated_Date", type="date", nullable=true)
     */
    private $lastUpdatedDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="archived_Date", type="date", nullable=true)
     */
    private $archivedDate;

    /**
     * @var \Activity
     *
     * @ORM\ManyToOne(targetEntity="Activity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_activity", referencedColumnName="id")
     * })
     */
    private $idActivity;

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
     *   @ORM\JoinColumn(name="last_updated_by", referencedColumnName="id")
     * })
     */
    private $lastUpdatedBy;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uploaded_by", referencedColumnName="id")
     * })
     */
    private $uploadedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkFile(): ?string
    {
        return $this->workFile;
    }

    public function setWorkFile(?string $workFile): self
    {
        $this->workFile = $workFile;

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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getUploadedDate(): ?\DateTimeInterface
    {
        return $this->uploadedDate;
    }

    public function setUploadedDate(?\DateTimeInterface $uploadedDate): self
    {
        $this->uploadedDate = $uploadedDate;

        return $this;
    }

    public function getLastUpdatedDate(): ?\DateTimeInterface
    {
        return $this->lastUpdatedDate;
    }

    public function setLastUpdatedDate(?\DateTimeInterface $lastUpdatedDate): self
    {
        $this->lastUpdatedDate = $lastUpdatedDate;

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

    public function getIdActivity(): ?Activity
    {
        return $this->idActivity;
    }

    public function setIdActivity(?Activity $idActivity): self
    {
        $this->idActivity = $idActivity;

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

    public function getLastUpdatedBy(): ?User
    {
        return $this->lastUpdatedBy;
    }

    public function setLastUpdatedBy(?User $lastUpdatedBy): self
    {
        $this->lastUpdatedBy = $lastUpdatedBy;

        return $this;
    }

    public function getUploadedBy(): ?User
    {
        return $this->uploadedBy;
    }

    public function setUploadedBy(?User $uploadedBy): self
    {
        $this->uploadedBy = $uploadedBy;

        return $this;
    }


}
