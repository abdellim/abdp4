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

   * @ORM\OneToMany(targetEntity="Morad\BilleterieBundle\Entity\Reservation", mappedBy="coordonnees")

   */
    private $coordonnees;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    
    private $id;


    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer", nullable=true)
     */
    
    private $prix;

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
     * Add coordonnee
     *
     * @param \Morad\BilleterieBundle\Entity\Reservation $coordonnee
     *
     * @return Reservation
     */
    public function addCoordonnee(\Morad\BilleterieBundle\Entity\Reservation $coordonnee)
    {
        $this->coordonnees[] = $coordonnee;

        return $this;
    }

    /**
     * Remove coordonnee
     *
     * @param \Morad\BilleterieBundle\Entity\Reservation $coordonnee
     */
    public function removeCoordonnee(\Morad\BilleterieBundle\Entity\Reservation $coordonnee)
    {
        $this->coordonnees->removeElement($coordonnee);
    }

    /**
     * Get coordonnees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoordonnees()
    {
        return $this->coordonnees;
    }

    public function check_dimanche($dateD) {
        preg_match(' /([0-9]+)\/([0-9]+)\/([0-9]+)/ ', $dateD , $match );
        $date = date("l", mktime(0, 0, 0, $match[2], $match[1], $match[3]));
        $date = trim($date);
        if (strstr($date,"Sunday")){
            return 1;
        }
        else {
            return 0;
        }
    }

    public function isNotWorkable($date)
    {
        if ($date === null)
        {
            $date = time()->format('d-M-Y');
        }

        $date = date('d-m-Y'); 
       //$date = '25/01/2017';
        $date = strtotime($date);
 
        $year = date('Y',$date);
 
        $easterDate  = easter_date($year);
        $easterDay   = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear   = date('Y', $easterDate);
 
        $holidays = array(
            // Dates fixes
            mktime(0, 0, 0, 1,  1,  $year),  // 1er janvier
            mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
            mktime(0, 0, 0, 5,  8,  $year),  // Victoire des alliés
            mktime(0, 0, 0, 7,  14, $year),  // Fête nationale
            mktime(0, 0, 0, 8,  15, $year),  // Assomption
            mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
            mktime(0, 0, 0, 11, 11, $year),  // Armistice
            mktime(0, 0, 0, 12, 25, $year),  // Noel
     
            // Dates variables
            mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),
            mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear),
        );
 
        return in_array($date, $holidays);
    }


    /**
     * Set prix
     *
     * @param integer $prix
     *
     * @return Reservation
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return integer
     */
    public function getPrix()
    {
        return $this->prix;
    }
}
