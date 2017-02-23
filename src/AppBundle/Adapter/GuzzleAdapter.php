<?php

namespace AppBundle\Adapter;

use AppBundle\Constants\ServiceConstant;
use AppBundle\Interfaces\HttpClientInterface;
use GuzzleHttp\Client;

class GuzzleAdapter implements HttpClientInterface
{

    protected $_client;

    /**
     * Performs a standard HTTP GET request
     *
     * @param string $url the url to get
     * @return array the Http response
     */
    public function get( $url ) {

        $response = $this->getClient()->get( $url );
        $contents = $response->getBody()->getContents();

        return $this->_jasonDecode( $contents );

    }

    /**
     * Return the guzzle client
     *
     * @return Client guzzle client
     */
    public function getClient() {

        if ( !$this->_client ) {

            $client = new Client( [
                'timeout'  => ServiceConstant::HTTP_CLIENT_TIMEOUT,
            ] );

            $this->setClient( $client );

        }

        return $this->_client;

    }

    /**
     * Sets the guzzle client
     *
     * @param Client $client guzzle instance
     */
    public function setClient( Client $client ) {

        $this->_client = $client;

    }

    /**
     * Decode a JSON string
     *
     * @return array the decoded string as an assoc array
     */
    private function _jasonDecode( $string ) {

        return json_decode( $string, true);

    }

}
