<?php

namespace AppBundle\Adapter;

use AppBundle\Interfaces\LoggerInterface;
use Psr\Log\LoggerInterface as SymfonyLoggerInterface;

class MonologAdapter implements LoggerInterface
{

    private $_logger;

    /**
     * Instantiates the service state
     *
     * @param SymfonyLoggerInterface $logger inject the logger
     */
    public function __construct( SymfonyLoggerInterface $logger ) {

        $this->_logger = $logger;

    }

    /**
     * Log an error message
     *
     * @param string $message the message to log
     * @param array  $context additional information
     *
     * @return void
     */
    public function error( $message, array $context = [] ) {

        $this->_logger->error( $message, $context );

    }

}
