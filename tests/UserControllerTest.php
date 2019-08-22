<?php


namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetUser()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users', [], [], ['HTTP_X-AUTH-TOKEN' => 'yacine', 'HTTP_ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json']);

        $response = $client->getResponse();
        $content = $response->getContent();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
        $arrayContent = \json_decode($content, true);
        $this->assertCount(12, $arrayContent);
    }

    public function testPostUsers()
    {
        $client = static::createClient();
        $client->request('GET','/api/users', [], [], [
            'HTTP_X-AUTH-TOKEN' => 'yacine',
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json'],

            '{"apiKey": "yacine","email": "yasby@hotmail.com"}'
            );

        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }

}