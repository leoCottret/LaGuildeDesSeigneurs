<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests index
 */
class CharacterControllerTest extends WebTestCase
{
    public function testDisplay()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/display');

        $this->assertJsonResponse($client->getResponse());
    }

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/character');

        $response = $client->getResponse();
        $this->assertJsonResponse($client->getResponse());
    }

    public function assertJsonResponse($response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }
}
