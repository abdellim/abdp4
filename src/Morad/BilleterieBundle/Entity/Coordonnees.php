<?php

namespace Morad\BilleterieBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Coordonnees
 *
 * @ORM\Table(name="coordonnees")
 * @ORM\Entity(repositoryClass="Morad\BilleterieBundle\Repository\CoordonneesRepository")
 */
class Coordonnees
{

      /**

   * @ORM\ManyToOne(targetEntity="Morad\BilleterieBundle\Entity\Reservation", inversedBy="coordonnees")

   * @ORM\JoinColumn(nullable=false)
  

   */
    private $reservation;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    private $prix;

    
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Le nom doit comporter au minimum 2 caractères !")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\Length(min=2, minMessage="Le prénom doit comporter au minimum 2 caractères !")
     */
    private $prenom;

    /**
     * @var BirthdayType
     *
     * @ORM\Column(name="dateDeNaissance", type="date")
     */
    private $dateDeNaissance;


    /**
     * @var \CountryType
     *
     * @ORM\Column(name="pays", type="string")
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="codeReservation", type="string", length=255, nullable=true)
     */
    private $codeReservation;

    /**
     * @var string
     *
     * @ORM\Column(name="tarifReduit", type="boolean", nullable=false)
     */
    private $tarifReduit = false;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Coordonnees
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }



    /**
     * Set tarifReduit
     *
     * @param boolean $tarifReduit
     *
     * @return Coordonnees
     */
    public function setTarifReduit($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * Get tarifReduit
     *
     * @return boolean
     */
    public function getTarifReduit()
    {
        return $this->tarifReduit;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Coordonnees
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateDeNaissance
     *
     * @param \Date $dateDeNaissance
     *
     * @return Coordonnees
     */
    public function setDateDeNaissance($dateDeNaissance)
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    /**
     * Get dateDeNaissance
     *
     * @return \DateTime
     */
    public function getDateDeNaissance()
    {
        return $this->dateDeNaissance;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Coordonnees
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set codeReservation
     *
     * @param string $codeReservation
     *
     * @return Coordonnees
     */
    public function setCodeReservation($codeReservation)
    {
        $this->codeReservation = $codeReservation;

        return $this;
    }

    /**
     * Get codeReservation
     *
     * @return string
     */
    public function getCodeReservation()
    {
        return $this->codeReservation;
    }




    public function getPrix($age, $journee)
    {
        $tarifReduit = $this->getTarifReduit();
        if ($age >= 12) {
            $prix = 16;
        }
        if ($age >= 4 && $age < 12) {
            $prix = 8;
            $tarifReduit = null; //Si la case est cocher le meilleur tarif s'applique
        }
        if ($age >= 60) {
            $prix = 12;
        }
        
        if ($tarifReduit == true) {
            $prix = 10;
        }
        if ($journee == 0) {
           $prix = $prix/2;
        }
        return $prix;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Coordonnees
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
     * Set reservation
     *
     * @param \Morad\BilleterieBundle\Entity\Reservation $reservation
     *
     * @return Coordonnees
     */
    public function setReservation(\Morad\BilleterieBundle\Entity\Reservation $reservation)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \Morad\BilleterieBundle\Entity\Reservation
     */
    public function getReservation()
    {
        return $this->reservation;
    }
}
