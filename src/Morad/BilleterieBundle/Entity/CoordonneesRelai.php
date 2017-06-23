<?php

namespace Morad\BilleterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoordonneesRelai
 *
 * @ORM\Table(name="coordonnees_relai")
 * @ORM\Entity(repositoryClass="Morad\BilleterieBundle\Repository\CoordonneesRelaiRepository")
 */
class CoordonneesRelai
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

