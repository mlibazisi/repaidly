<?php

namespace AppBundle\Tests\Adapter;

use AppBundle\Adapter\GuzzleAdapter;

class GuzzleAdapterTest extends \PHPUnit_Framework_TestCase
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
        $url              = 'http://example.com';
        $guzzle_response  = $this->getMock( 'Psr\Http\Message\ResponseInterface' );
        $response_body    = $this->getMock( 'Psr\Http\Message\StreamInterface' );

        $response_body->expects( $this->once() )
            ->method( 'getContents' )
            ->will( $this->returnValue( json_encode( $response ) ) );

        $guzzle_response->expects( $this->once() )
            ->method( 'getBody' )
            ->will( $this->returnValue( $response_body ) );

        $guzzle = $this->getMockBuilder( 'GuzzleHttp\Client' )
            ->disableOriginalConstructor()
            ->getMock();

        $guzzle->expects($this->once())
            ->method('__call')
            ->with(
                $this->equalTo('get'),
                $this->equalTo([ $url ])
            )->will( $this->returnValue( $guzzle_response ) );

        $guzzleAdapter = new GuzzleAdapter();
        $guzzleAdapter->setClient( $guzzle );
        $result = $guzzleAdapter->get( $url );

        $this->assertEquals( $response, $result );

    }

    public function testSetClient()
    {

        $guzzle = $this->getMockBuilder( 'GuzzleHttp\Client' )
            ->disableOriginalConstructor()
            ->getMock();

        $guzzleAdapter = new GuzzleAdapter();
        $guzzleAdapter->setClient( $guzzle );
        $result = $guzzleAdapter->getClient();

        $this->assertEquals( $guzzle, $result );

    }

}