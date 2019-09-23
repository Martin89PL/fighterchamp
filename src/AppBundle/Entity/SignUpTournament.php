<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SignUpTournamentRepository")
 * @ORM\Table(name="signuptournament")
 */
class SignUpTournament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $weight;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $weighted;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $formula;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tournament", inversedBy="signUpTournament")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="signUpTournaments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPaid = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLicence = false;

    /**
     * @ORM\Column(type="datetime",name="deleted_at",  nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAtByAdmin;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\Range(
     *      min = 1,
     *      max = 100
     * )
     */
    private $trainingTime;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $youtubeId = '';

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string
     */
    private $musicArtistAndTitle = '';

    public function __construct(User $user, Tournament $tournament)
    {
        $this->user = $user;
        $this->tournament = $tournament;
        $this->createdAt = new \DateTime();
    }

    public function getIsLicence() : bool
    {
        return $this->isLicence;
    }

    public function setIsLicence($isLicence): void
    {
        $this->isLicence = $isLicence;
    }

    public function getTrainingTime() :?int
    {
        return $this->trainingTime;
    }

    public function setTrainingTime($trainingTime) : void
    {
        $this->trainingTime = $trainingTime;
    }

    public function getDeletedAt() : ? \DateTime
    {
        return $this->deletedAt;
    }

    public function delete() : void
    {
        $this->deletedAt = new \DateTime();
    }

    public function isPaid() : bool
    {
        return $this->isPaid;
    }

    public function setIsPaid($isPaid) : void
    {
        $this->isPaid = $isPaid;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getWeight(): ?string
    {
        return $this->weight;
    }

    public function setWeight($weight) : void
    {
        $this->weight = $weight;
    }

    public function getFormula() : ?string
    {
        return $this->formula;
    }

    public function setFormula($formula) : void
    {
        $this->formula = $formula;
    }

    public function getTournament() : Tournament
    {
        return $this->tournament;
    }

    public function setTournament($tournament) : void
    {
        $this->tournament = $tournament;
    }

    public function getUser() : User
    {
        return $this->user;
    }

    public function setUser(User $user) : void
    {
        $this->user = $user;
    }

    public function getTrainingInternship() : ?string
    {
        if($this->getTrainingTime()){
            return '(staż ' . $this->getTrainingTime() . "miesiące) ";
        }
        return null;
    }

    public function getWeighted() : ?string
    {
        return $this->weighted;
    }

    public function setWeighted($weighted) : void
    {
        $this->weighted = $weighted;
    }

    public function getFinalWeight()
    {
        return $this->weighted ?? $this->weight;
    }


    public function getYoutubeId(): ?string
    {
        return $this->youtubeId;
    }

    public function setYoutubeId(?string $youtubeId = ''): void
    {
        $this->youtubeId = $youtubeId;
    }

    public function getMusicArtistAndTitle(): ?string
    {
        return $this->musicArtistAndTitle;
    }

    public function setMusicArtistAndTitle(?string $musicArtistAndTitle = '') : void
    {
        $this->musicArtistAndTitle = $musicArtistAndTitle;
    }

    public function getDeletedAtByAdmin(): ?\DateTime
    {
        return $this->deletedAtByAdmin;
    }

    public function setDeleteByAdmin(?\DateTime $dateTime): void
    {
        $this->deletedAtByAdmin = $dateTime;
    }
}
