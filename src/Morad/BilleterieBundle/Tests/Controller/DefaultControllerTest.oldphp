<?php

namespace Morad\BilleterieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUunit\Framework\TestCase;

class CoordonneesRepositoryTest extends TestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Hello World', $client->getResponse()->getContent());
    }

    //creer des methodes pour tester des methodes

    public function testCannotGetFromInvalidId() 
    {
    	$this-> expectException(InvalidArgumentException:: class);
    	CoordonneesRepository:: myFindDQL('a');
    }
}
