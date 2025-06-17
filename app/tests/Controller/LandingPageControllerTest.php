<?php

/**
 * Landing page controller test.
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class LandingPageControllerTest.
 */
class LandingPageControllerTest extends WebTestCase
{
    /**
     * Landing page /ebay route test.
     */
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/ebay');

        $this->assertResponseIsSuccessful();
    }
}
