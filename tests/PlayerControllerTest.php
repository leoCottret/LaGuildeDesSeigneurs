<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests
 */
class PlayerControllerTest extends WebTestCase
{
    private $content;
    private static $identifier;
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testPlayerCreate()
    {
        $crawler = $this->client->request('POST', 
        '/player/create', 
         [],//parameters
         [],//files
          array('CONTENT_TYPE' => 'application/json'),//Server
         '{"lastname":"cotré","firstname":"leo","email":"a@a.a","mirian":6}');

        $this->assertJsonResponse();
        $this->defineIdentifier();
        $this->assertIdentifier();
    }

    public function testPlayerRedirectIndex()
    {
        $crawler = $this->client->request('GET', '/player');

        $response = $this->client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testPlayerBadIdentifier()
    {
        $crawler = $this->client->request('GET', '/player/display/badIdentifier');

        $response = $this->client->getResponse();
        $this->assertError404($response->getStatusCode());
    }

    public function testPlayerInexistingIdentifier()
    {
        $crawler = $this->client->request('GET', '/player/display/49d5594c71cb0747bcd9d39c939561571c47f479');

        $response = $this->client->getResponse();
        $this->assertError404($response->getStatusCode());
    }

    public function testPlayerModify()
    {
        //Test with whole content
        $crawler = $this->client->request(
            'PUT', 
            '/player/modify/'.self::$identifier, 
             [],//parameters
             [],//files
              array('CONTENT_TYPE' => 'application/json'),//Server
              '{"mirian":7}');
        $this->assertJsonResponse();
        $this->assertIdentifier();

        //Test with partial data array
        $crawler = $this->client->request(
            'PUT', 
            '/player/modify/'.self::$identifier, 
             [],//parameters
             [],//files
              array('CONTENT_TYPE' => 'application/json'),//Server
              '{"lastname":"cotrééé","firstname":"leooo","email":"a@aaa.a","mirian":8}');
        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testPlayerDisplay()
    {
        $crawler = $this->client->request('GET', '/player/display/'.self::$identifier);

        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testPlayerIndex()
    {
        $crawler = $this->client->request('GET', '/player/index');

        $response = $this->client->getResponse();
        $this->assertJsonResponse();
    }

    public function testPlayerDelete()
    {
        $crawler = $this->client->request('DELETE', '/player/delete/'.self::$identifier);

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
