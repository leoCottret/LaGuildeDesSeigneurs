<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests index
 */
class CharacterControllerTest extends WebTestCase
{
    private $content;
    private static $identifier;
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testCreate()
    {
        $crawler = $this->client->request('POST', '/character/create');
        $this->assertJsonResponse();
        $this->defineIdentifier();
        $this->assertIdentifier();
    }

    public function testRedirectIndex()
    {
        $crawler = $this->client->request('GET', '/character');

        $response = $this->client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testBadIdentifier()
    {
        $crawler = $this->client->request('GET', '/character/display/badIdentifier');

        $response = $this->client->getResponse();
        $this->assertError404($response->getStatusCode());
    }

    public function testInexistingIdentifier()
    {
        $crawler = $this->client->request('GET', '/character/display/49d5594c71cb0747bcd9d39c939561571c47f479');

        $response = $this->client->getResponse();
        $this->assertError404($response->getStatusCode());
    }

    public function testModify()
    {
        $crawler = $this->client->request('PUT', '/character/modify/'.self::$identifier);
        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testDisplay()
    {
        $crawler = $this->client->request('GET', '/character/display/'.self::$identifier);

        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/character/index');

        $response = $this->client->getResponse();
        $this->assertJsonResponse();
    }

    public function testDelete()
    {
        $crawler = $this->client->request('DELETE', '/character/delete/'.self::$identifier);

        $this->assertJsonResponse();
    }

    public function assertJsonResponse()
    {
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertTrue($this->client->getResponse()->headers->contains('Content-Type', 'application/json'), $this->client->getResponse()->headers);
    }

    public function assertError404($statusCode)
    {
        $this->assertEquals(404, $statusCode);
    }

    /**
     * Assert that identifier is present in the response
     */
    public function assertIdentifier()
    {
        $this->assertArrayHasKey('identifier', $this->content);
    }


    /**
     * Defines identifier
     */
    public function defineIdentifier()
    {
        self::$identifier = $this->content['identifier'];
    }
}
