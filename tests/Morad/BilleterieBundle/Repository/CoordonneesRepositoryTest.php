<?php

namespace Tests\Morad\BilleterieBundle;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


//Vérifie si il l'id existe = envoi une erreur
class CoordonneesRepositoryTest extends KernelTestCase
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

    public function testSearchCoordonneesById()
    {
        $coordonnees = $this->em
            ->getRepository('MoradBilleterieBundle:Coordonnees')
            ->myFindDQL('215')
        ;
        print(implode(',', $coordonnees[0]));
        $this->assertTrue(count($coordonnees) != 0);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

}