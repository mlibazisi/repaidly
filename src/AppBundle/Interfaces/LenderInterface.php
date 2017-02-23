<?php

namespace AppBundle\Interfaces;

Interface LenderInterface
{

    public function findAll( $endpoint );

    public function getSchedule( $loan_id, $lender_id );

}
