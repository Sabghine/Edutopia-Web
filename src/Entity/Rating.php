<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rating", indexes={@ORM\Index(name="id_rater", columns={"id_rater"}), @ORM\Index(name="id_item", columns={"id_item"})})
 * @ORM\Entity(repositoryClass="App\Repository\RatingRepository")
 */
class Rating
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
     * @var int
     *
     * @ORM\Column(name="rate", type="integer", nullable=false)
     */
    private $rate;

    /**
     * @var \CoStudying
     *
     * @ORM\ManyToOne(targetEntity="CoStudying")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_item", referencedColumnName="id")
     * })
     */
    private $idItem;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_rater", referencedColumnName="id")
     * })
     */
    private $idRater;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getIdItem(): ?CoStudying
    {
        return $this->idItem;
    }

    public function setIdItem(?CoStudying $idItem): self
    {
        $this->idItem = $idItem;

        return $this;
    }

    public function getIdRater(): ?User
    {
        return $this->idRater;
    }

    public function setIdRater(?User $idRater): self
    {
        $this->idRater = $idRater;

        return $this;
    }


}
