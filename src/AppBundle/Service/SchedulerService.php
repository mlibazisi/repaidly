<?php

namespace AppBundle\Service;

use AppBundle\Interfaces\SchedulerInterface;
use Symfony\Component\DependencyInjection\Container;

class SchedulerService implements SchedulerInterface
{

    const PRECISION = 4;

    /**
     * Calculates the repayment schedule of a loan
     *
     * @param string $loan_amount the loan amount
     * @param string $disbursal_date the disbursal date
     * @param string $term the repayment term in months
     *
     * @return array the repayment schedule
     */
    function computeBorrowerSchedule( $loan_amount, $disbursal_date, $term ) {

        $schedule   = [];
        $paid       = 0;
        $amount     = $loan_amount;

        for( $i = $term; $i > 0; $i-- ) {

            $dollars = floor( round( $amount / $i, self::PRECISION, PHP_ROUND_HALF_DOWN ) );

            for( $inner = 0; $inner < $i; $inner++ ) {

                if ( isset( $schedule[ $inner ] ) ) {
                    $schedule[ $inner ]['payment_amount'] += $dollars;
                } else {

                    $disbursal_date = date( 'Y-m-d', strtotime( "+1 month", strtotime( $disbursal_date ) ) );
                    $schedule[ $inner ] = [
                        'payment_date'   => $disbursal_date,
                        'payment_amount' => $dollars
                    ];

                }

                $paid += $dollars;

            }

            $amount = $amount - ( $dollars * $i );

        }

        $lost = $loan_amount - $paid;

        if ( $lost ) {
            $schedule[ 0 ]['payment_amount'] += (float)$lost;
        }


        return $schedule;

    }

    function computeLenderSchedule( array $borrower_schedule, $total_lenders ) {

        $schedule       = [];
        $last_index     = -1;
        $total_paid     = 0;
        $total_received = 0;

        foreach ( $borrower_schedule as $repayment ) {

            $last_index++;
            $receipt_amount = round( $repayment['payment_amount'] / $total_lenders, self::PRECISION, PHP_ROUND_HALF_DOWN );
            $schedule[]     = [
                'receipt_date'   => $this->_incrementMonth( $repayment['payment_date'] ),
                'receipt_amount' => $receipt_amount
            ];

            $total_received += $receipt_amount * $total_lenders;
            $total_paid     += $repayment['payment_amount'];

        }

        $lost = $total_paid - $total_received;

        if ( $lost ) {

            $recoverable = round( $lost / $total_lenders, self::PRECISION, PHP_ROUND_HALF_DOWN );

            if ( $recoverable ) {
                $schedule[ $last_index ]['receipt_amount'] += $recoverable;
            }

        }

        return $schedule;

    }

    private function _incrementMonth( $date ) {

        return date( 'Y-m-d', strtotime( "+1 month", strtotime( $date ) ) );

    }

}
