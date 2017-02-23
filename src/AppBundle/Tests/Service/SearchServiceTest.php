<?php

namespace AppBundle\Tests\Service;

use AppBundle\Constants\ServiceConstant;
use AppBundle\Service\SearchService;

class SearchServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testQuery()
    {

        $response = [
            'loans'   => [
                [ 'loan' ]
            ],
            'paging'  => [
                'page'      => 1,
                'total'     => 36,
                'page_size' => 10,
                'pages'     => 742
            ]
        ];

        $url        = 'http://example.com';
        $container  = $this->getMock( 'Symfony\Component\DependencyInjection\Container' );

        $http_client = $this->getMock( 'AppBundle\Interfaces\HttpClientInterface' );

        $http_client->expects( $this->once() )
            ->method( 'get' )
            ->with( $this->equalTo( $url ) )
            ->will( $this->returnValue( $response ) );

        $container->expects( $this->once() )
            ->method( 'get' )
            ->with( ServiceConstant::HTTP_SERVICE )
            ->will( $this->returnValue( $http_client ) );

        $search = new SearchService( $container );
        $result = $search->query( $url );

        $this->assertEquals( $response, $result );

    }

    public function testQueryWithMalformedResponse()
    {

        $response = [
            'loans'   => [],
            'paging'  => [
                'page'      => 0,
                'total'     => 0,
                'page_size' => 0,
                'pages'     => 0
            ]
        ];

        $malformed = [
            'loans'   => 'loan_string',
            'paging'  => 'pagin_string'
        ];

        $url         = 'http://example.com';
        $container   = $this->getMock( 'Symfony\Component\DependencyInjection\Container' );
        $http_client = $this->getMock( 'AppBundle\Interfaces\HttpClientInterface' );

        $http_client->expects( $this->once() )
            ->method( 'get' )
            ->with( $this->equalTo( $url ) )
            ->will( $this->returnValue( $malformed ) );

        $container->expects( $this->once() )
            ->method( 'get' )
            ->with( ServiceConstant::HTTP_SERVICE )
            ->will( $this->returnValue( $http_client ) );

        $search = new SearchService( $container );
        $result = $search->query( $url );

        $this->assertEquals( $response, $result );

    }

}