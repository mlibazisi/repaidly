<?php

namespace AppBundle\Interfaces;

Interface HttpClientInterface
{

    /**
     * Performs a standard HTTP GET request
     *
     * @param string $url the url to get
     * @return mixed the Http response
     */
    public function get( $url );

}
