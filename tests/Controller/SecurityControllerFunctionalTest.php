<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerFunctionalTest extends WebTestCase
{
    public function testLoginDisplay()
    {
        $client = static::createClient();

        // Request the /login page
        $crawler = $client->request('GET', '/login');

        // Check that the response status code is 200
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Check that the page contains a form with an input field with name "_username"
        $this->assertNotNull($crawler->filter('input[name="_username"]'));

    }

}
