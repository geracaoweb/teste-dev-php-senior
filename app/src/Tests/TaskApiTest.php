<?php

namespace Acme\Tests;

use Silex\WebTestCase;

/**
 * Undocumented class
 */
class TagsApiTest extends WebTestCase {

    /**
    * SetUp Method
    */
    public function setUp() {
        parent::setUp();
    }
    
    /**
    * Garante nossa instância de do Application
    * @return [type] [description]
    */
    public function createApplication() {
        $app = require __DIR__ . '/../Configs/Bootstrap.php';
        return $app;
    }

    /**
     * Undocumented function
     * @return void
     */
    public function testList() {
        $client = $this->createClient();
        $client->request('GET', '/task/');
        
        $response = json_decode($client->getResponse()->getContent());
                
        $this->assertEquals(200, $client->getInternalResponse()->getStatus());
        $this->assertEquals('application/json', $client->getInternalResponse()->getHeaders()['content-type'][0]);
    }
}