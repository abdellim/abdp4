<?php

namespace Tests\Morad\BilleterieBundle;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


//Vérifie si il l'id existe = envoi une erreur
class ReservationRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSearchReservationById()
    {
        $Reservation = $this->em
            ->getRepository('MoradBilleterieBundle:Reservation')
            ->find('217')
        ;
        $this->assertTrue(count($Reservation) != 0);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; 
    }

}