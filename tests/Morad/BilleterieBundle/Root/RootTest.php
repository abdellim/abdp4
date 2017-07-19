<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RootTest extends WebTestCase {

	public function testRoot() {

		$client = static::createClient();
		$crawler = $client->request('GET', '/mentions_legales');
		$this->assertEquals(1, $crawler->filter('h2:contains("Mentions légales")')->count());
	}
}