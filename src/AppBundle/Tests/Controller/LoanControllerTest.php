<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoanControllerTest extends WebTestCase
{

    public function testIndex()
    {

        $loan_id = 1231374;
        $client  = static::createClient();
        $crawler = $client->request('GET', '/loans/' . $loan_id );

        $this->assertEquals( 200, $client->getResponse()->getStatusCode() );
        $this->assertContains( 'Borrower schedule', $crawler->filter('#payment-schedule h4')->text() );

    }

}
