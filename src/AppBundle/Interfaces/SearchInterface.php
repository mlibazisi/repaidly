<?php

namespace AppBundle\Interfaces;

Interface SearchInterface
{

    /**
     * Returns the search results
     *
     * @param string $endpoint the url to query
     * @return array an array of the search results
     */
    public function query( $endpoint );

}
