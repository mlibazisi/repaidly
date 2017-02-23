<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{

    public function testIndex()
    {

        $query   = 'Zimbabwe';
        $client  = static::createClient();
        $url     = '/search?q=' . $query;
        $crawler = $client->request('GET', $url );

        $this->assertEquals( 200, $client->getResponse()->getStatusCode() );
        $this->assertContains( $query, $crawler->filterXPath('//div[@class="location"][1]/p')->first()->text() );

    }

}
