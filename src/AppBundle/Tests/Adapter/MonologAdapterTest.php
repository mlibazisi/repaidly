<?php

namespace AppBundle\Tests\Adapter;

use AppBundle\Adapter\MonologAdapter;

class MonologAdapterTest extends \PHPUnit_Framework_TestCase
{

    public function testError()
    {

        $message = 'Error';
        $context = [ 'context' ];
        $monolog = $this->getMock( 'Psr\Log\LoggerInterface' );

        $monolog->expects( $this->once() )
            ->method( 'error' )
            ->with(
                $this->equalTo( $message ),
                $this->equalTo( $context )
            );


        $monologAdapter = new MonologAdapter( $monolog );
        $monologAdapter->error( $message, $context );

    }

}