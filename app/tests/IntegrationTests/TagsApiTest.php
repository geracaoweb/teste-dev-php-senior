<?php
namespace Acme\Tests;

use Silex\WebTestCase;

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
        $app = require __DIR__ . '/../../web/Configs/Bootstrap.php';
        return $app;
    }
}