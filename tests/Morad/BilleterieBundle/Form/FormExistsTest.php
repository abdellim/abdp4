<?php

use PHPUnit\Framework\TestCase;

class FormExistsTest extends TestCase
{
    public function testFormReservation()
    {
        $this->assertFileExists(dirname(__FILE__).'\\..\\..\\..\\..\\src\\Morad\\BilleterieBundle\\Resources\\views\\Home\\form\\form.html.twig');
    }

    public function testFormCoordonnees()
    {
        $this->assertFileExists(dirname(__FILE__).'\\..\\..\\..\\..\\src\\Morad\\BilleterieBundle\\Resources\\views\\Home\\form\\contentFormCoordonnees.html.twig');
    }
    
}