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
     * @var integer
     *
     * @ORM\Column(name="quantite", type="integer")
     * @Assert\Range(min=1, max=10, minMessage="Veuillez selectionner au moins 1 billet !", maxMessage="Vous ne pouvez pas commander plus de 10 billet par réservation !")
     */
    private $quantite;

    /**
     * @var BirthdayType
     *
     * @ORM\Column(name="dateDeNaissance", type="datetime")
     */
    private $dateDeNaissance;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Reservation
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
     * Set codeReservation
     *
     * @param string $codeReservation
     *
     * @return Reservation
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
     * Set dateDeNaissance
     *
     * @param \DateTime $dateDeNaissance
     *
     * @return Reservation
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

    public function getAge()
    {
        $datetime2 = $this->getDateDeNaissance();
        if (is_null($datetime2)) {
            $datetime1 = $this->getDate();
            $datetime2 = $this->getDateDeNaissance();
            $age = '';
        } else {
            $datetime1 = $this->getDate();
            $datetime2 = $this->getDateDeNaissance();
            $age = $datetime1->diff($datetime2, true)->y;        
        }
        return $age;
    }

    public function getPrix()
    {
        $prix = 0;
        $age = $this->getAge();
        $journee = $this->getbillet();
        $quantite = $this->getQuantite();

        if ($age >= 12) {
            $prix = 16;
        }
        if ($age > 4 && $age < 12) {
            $prix = 8;
        }
        if ($age > 60) {
            $prix = 12;
        }
        if ($journee == 0) {
           $prix = $prix/2;
        }
        if ($prix != 0) {
            $prix = $prix*$quantite;
        }
        return $prix;
    }


    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Reservation
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Reservation
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
     * Set tarifReduit
     *
     * @param boolean $tarifReduit
     *
     * @return Reservation
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
}
