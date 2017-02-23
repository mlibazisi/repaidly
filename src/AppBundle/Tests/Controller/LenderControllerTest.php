<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LenderControllerTest extends WebTestCase
{

    public function testGetLenders()
    {

        $loan_id    = 1236356;
        $url        = "/loans/{$loan_id}/lenders";
        $client     = static::createClient();
        $page_size  = 50;
        $total      = 89;

        $client->request('GET', $url, [], [], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);

        $response = $client->getResponse();
        $this->assertEquals( 200, $response->getStatusCode() );
        $responseData = json_decode( $response->getContent(), true );

        $this->assertNotEmpty( $responseData['data'] );
        $this->assertNotEmpty( $responseData['data']['paging'] );
        $this->assertEquals( $page_size, $responseData['data']['paging']['page_size'] );
        $this->assertEquals( $total, $responseData['data']['paging']['total'] );

        $this->assertNotEmpty( $responseData['data']['lenders'] );

    }

    public function testGetLendersWithoutAjax()
    {

        $loan_id    = 1236356;
        $url        = "/loans/{$loan_id}/lenders";
        $client     = static::createClient();

        $client->request('GET', $url );
        $this->assertEquals( 500, $client->getResponse()->getStatusCode() );

    }

    public function testGetLenderSchedule()
    {

        $loan_id    = 1236356;
        $lender_id  = 'jaychapman';
        $url        = "/loans/{$loan_id}/lenders/{$lender_id}";
        $client     = static::createClient();
        $crawler    = $client->request('GET', $url, [], [], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);

        $this->assertEquals( 200, $client->getResponse()->getStatusCode() );
        $this->assertContains( $lender_id, $crawler->filter('#lender-id')->text() );

    }

    public function testGetLenderScheduleWithoutAjax()
    {

        $loan_id    = 1236356;
        $lender_id  = 'jaychapman';
        $url        = "/loans/{$loan_id}/lenders/{$lender_id}";
        $client     = static::createClient();

        $client->request('GET', $url );
        $this->assertEquals( 500, $client->getResponse()->getStatusCode() );

    }

}
