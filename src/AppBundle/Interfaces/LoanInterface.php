<?php

namespace AppBundle\Interfaces;

Interface LoanInterface
{

    /**
     * Retrieve a loan by its API endpoint
     *
     * @param string $endpoint the loan endpoint
     * @return array the loan details
     */
    public function getLoan( $endpoint );

}
