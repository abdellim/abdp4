<?php

namespace Morad\BilleterieBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="Morad\BilleterieBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */

    private $coordonnees;
    
    private $id;

    /**
     * @ORM\Column(name="billet", type="boolean")
     */
    private $billet = true;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255)
     * @Assert\Email(checkMX=true, message="L'adresse mail n'est pas valide!")
     */
    private $email;


    /**
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     * @Assert\Range(min=1, max=10, minMessage="Veuillez selectionner au moins 1 billet !", maxMessage="Vous ne pouvez pas commander plus de 10 billet par réservation !")
     */
    private $quantite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


    /**
     * Get id
     *
     * @return int
     */

    public function __construct()
    {
        // Par défaut, la date de reservation est la date d'aujourd'hui

        $this->date = new \Datetime();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reservation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set Type de billet
     *
     * @param boolean $Typedebillet
     *
     * @return Reservation
     */
    public function setbillet($billet)
    {
        $this->billet = $billet;

        return $this;
    }

    /**
     * Get journee
     *
     * @return boolean
     */
    public function getbillet()
    {
        return $this->billet;
    }

    /**
     * Set quantite
     *
     * @param \integer $quantite
     *
     * @return Reservation
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return \integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }





    /**
     * Get coordonnees
     *
     * @return Reservation
     */
    public function getCoordonnees()
    {
        return $this->coordonnees;
    }
}
