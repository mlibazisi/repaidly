<?php

namespace AppBundle\Interfaces;

Interface LoggerInterface
{

    /**
     * Log an error message
     *
     * @param string $message the message to log
     * @param array  $context additional information
     *
     * @return void
     */
    public function error( $message, array $context = [] );

}
