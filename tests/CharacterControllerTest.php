<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Tests index
 * Exemple de test
 * Pour lancer les tests
 * ./vendor/bin/phpunit tests/Controller/ActionControllerTest
 *
 * Pour lancer un test
 * ./vendor/bin/phpunit --filter testPostIncompleteLossActionFails
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

    public function testCharacterCreate()
    {
        $crawler = $this->client->request(
            'POST',
            '/character/create',
            [],//parameters
         [],//files
          array('CONTENT_TYPE' => 'application/json'),//Server
         '{"kind":"Dames","name":"Eldalótës","surname":"Fleur elfiques","caste":"Elfes","knowledge":"Artss","intelligence":1200,"life":122,"image":"/images/eldalote.jpg"}'
        );

        $this->assertJsonResponse();
        $this->defineIdentifier();
        $this->assertIdentifier();
    }

    public function testCharacterRedirectIndex()
    {
        $crawler = $this->client->request('GET', '/character');

        $response = $this->client->getResponse();
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testCharacterBadIdentifier()
    {
        $crawler = $this->client->request('GET', '/character/display/badIdentifier');

        $response = $this->client->getResponse();
        $this->assertError404($response->getStatusCode());
    }

    public function testCharacterInexistingIdentifier()
    {
        $crawler = $this->client->request('GET', '/character/display/49d5594c71cb0747bcd9d39c939561571c47f479');

        $response = $this->client->getResponse();
        $this->assertError404($response->getStatusCode());
    }

    public function testCharacterModify()
    {
        //Test with whole content
        $crawler = $this->client->request(
            'PUT',
            '/character/modify/'.self::$identifier,
            [],//parameters
             [],//files
              array('CONTENT_TYPE' => 'application/json'),//Server
              '{"kind":"Seigneur","name":"Gorthol","surname":"Fleur elfiquesGor","caste":"ElfesGor","knowledge":"ArtssGor","intelligence":110,"life":13,"image":"/images/gorthol.jpg"}'
        );
        $this->assertJsonResponse();
        $this->assertIdentifier();

        //Test with partial data array
        $crawler = $this->client->request(
            'PUT',
            '/character/modify/'.self::$identifier,
            [],//parameters
             [],//files
              array('CONTENT_TYPE' => 'application/json'),//Server
              '{"kind":"Seigneur", "name":"Gorthol"}'
        );
        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testCharacterDisplay()
    {
        $crawler = $this->client->request('GET', '/character/display/'.self::$identifier);

        $this->assertJsonResponse();
        $this->assertIdentifier();
    }

    public function testCharacterIndex()
    {
        $crawler = $this->client->request('GET', '/character/index');

        $this->assertJsonResponse();
    }

    public function testCharacterMoreIntelligentThan()
    {
        $crawler = $this->client->request('GET', 'character/display/more_intelligent_than/250');

        $this->assertJsonResponse();
    }

    public function testCharacterMoreIntelligentThanNoLimitIntelligenceParameter()
    {
        $crawler = $this->client->request('GET', 'character/display/more_intelligent_than/');
        
        $this->assertError404($this->client->getResponse()->getStatusCode());
    }

    public function testCharacterDelete()
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
