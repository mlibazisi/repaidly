<?php

namespace AppBundle\Service;

use AppBundle\Exception\ServiceException;
use AppBundle\Interfaces\HttpClientInterface;
use AppBundle\Interfaces\LoggerInterface;

class HttpClientService implements HttpClientInterface
{

    private $_http_client;
    private $_logger;

    /**
     * Instantiates the service state
     *
     * @param HttpClientInterface $client inject an http client instance
     * @param LoggerInterface $logger inject a logger instance
     */
    public function __construct( HttpClientInterface $client, LoggerInterface $logger ) {

        $this->_http_client = $client;
        $this->_logger      = $logger;

    }

    /**
     * Performs a standard HTTP GET request
     *
     * @param string $url the url to get
     * @return mixed the Http response
     * @throws ServiceException if it fails to load the http client
     */
    public function get( $url ) {

        try {

            return $this->_http_client->get( $url );

        } catch ( \Exception $e ) {

            $message = $e->getMessage();
            $this->_logger->error( $message, $context = [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'called_by' => 'HttpClientService::get'
            ] );

            throw new ServiceException( $message );

        }

    }

}
