<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests index
 */
class CharacterControllerTest extends WebTestCase
{

    public function testRedirectIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/character');

        $response = $client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testDisplay()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/character/display/59d5594c71cb0747bcd9d39c939561571c47f479');

        $this->assertJsonResponse($client->getResponse());
    }

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/character/index');

        $response = $client->getResponse();
        $this->assertJsonResponse($client->getResponse());
    }

    public function assertJsonResponse($response)
    {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }
}
