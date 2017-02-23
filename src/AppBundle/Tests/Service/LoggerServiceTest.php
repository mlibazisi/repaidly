<?php

namespace AppBundle\Tests\Service;

use AppBundle\Service\LoggerService;

class LoggerServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testError()
    {

        $message        = 'Error';
        $context        = [ 'context' ];
        $logger_client  = $this->getMock( 'AppBundle\Interfaces\LoggerInterface' );

        $logger_client->expects( $this->once() )
            ->method( 'error' )
            ->with(
                $this->equalTo( $message ),
                $this->equalTo( $context )
            );

        $logger_service = new LoggerService( $logger_client );
        $logger_service->error( $message, $context );

    }

}