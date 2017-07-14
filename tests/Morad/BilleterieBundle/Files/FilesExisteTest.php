<?php

use PHPUnit\Framework\TestCase;

class FileExistsTest extends TestCase
{
    public function testFileMentionsLegales()
    {
        $this->assertFileExists(dirname(__FILE__).'\\..\\..\\..\\..\\src\\Morad\\BilleterieBundle\\Resources\\views\\Home\\mentions\\mentionsLegales.html.twig');
    }

    public function testFileIndex()
    {
        $this->assertFileExists(dirname(__FILE__).'\\..\\..\\..\\..\\src\\Morad\\BilleterieBundle\\Resources\\views\\Home\\index.html.twig');
    }

    public function testFileCoordonnees()
    {
        $this->assertFileExists(dirname(__FILE__).'\\..\\..\\..\\..\\src\\Morad\\BilleterieBundle\\Resources\\views\\Home\\Coordonnees.html.twig');
    }

    public function testFilePaiement()
    {
        $this->assertFileExists(dirname(__FILE__).'\\..\\..\\..\\..\\src\\Morad\\BilleterieBundle\\Resources\\views\\Home\\Paiement.html.twig');
    }
    
}