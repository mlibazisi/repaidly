<?php

namespace AppBundle\Tests\Service;

use AppBundle\Service\HttpClientService;

class HttpClientServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testGet()
    {

        $response = [
            'loans'   => [],
            'paging'  => [
                'page'      => 1,
                'total'     => 36,
                'page_size' => 10,
                'pages'     => 742
            ]
        ];
        $url           = 'http://example.com';
        $http_client   = $this->getMock( 'AppBundle\Interfaces\HttpClientInterface' );
        $logger_client = $this->getMock( 'AppBundle\Interfaces\LoggerInterface' );

        $http_client->expects( $this->once() )
            ->method( 'get' )
            ->with( $this->equalTo( $url ) )
            ->will( $this->returnValue( $response ) );

        $http_adapter = new HttpClientService( $http_client, $logger_client );
        $result = $http_adapter->get( $url );

        $this->assertEquals( $response, $result );

    }

    public function testGetWithException()
    {

        $this->setExpectedException( '\AppBundle\Exception\ServiceException' );

        $url           = 'http://example.com';
        $http_client   = $this->getMock( 'AppBundle\Interfaces\HttpClientInterface' );
        $logger_client = $this->getMock( 'AppBundle\Interfaces\LoggerInterface' );

        $http_client->expects( $this->once() )
            ->method( 'get' )
            ->with( $this->equalTo( $url ) )
            ->will( $this->throwException( new \Exception() ) );

        $http_adapter = new HttpClientService( $http_client, $logger_client );
        $http_adapter->get( $url );

    }

}