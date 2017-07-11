<?php

use PHPUnit\Framework\TestCase;

class MentionsLegalesExistsTest extends TestCase
{
    public function testFailure()
    {
        $this->assertFileExists(dirname(__FILE__).'\\..\\..\\..\\..\\src\\Morad\\BilleterieBundle\\Resources\\views\\Home\\mentions\\mentionsLegales.html.twig');
    }
    
}