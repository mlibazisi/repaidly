<?php

namespace AppBundle\Service;

use AppBundle\Interfaces\LoggerInterface;

class LoggerService implements LoggerInterface
{

    private $_logger;

    /**
     * Instantiates the service state
     *
     * @param LoggerInterface $logger inject a logger instance
     */
    public function __construct( LoggerInterface $logger ) {

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
