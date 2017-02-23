<?php

namespace AppBundle\Service;

use AppBundle\Constants\ModelConstant;
use AppBundle\Constants\ServiceConstant;
use AppBundle\Exception\ServiceException;
use AppBundle\Interfaces\LoanInterface;
use Symfony\Component\DependencyInjection\Container;

class LoanService implements LoanInterface
{

    private $_container;

    /**
     * Instantiates the service state
     *
     * @param Container $container the symfony service container
     */
    public function __construct( Container $container ) {

        $this->_container = $container;

    }

    /**
     * Retrieve a loan by its API endpoint
     *
     * @param string $endpoint the loan endpoint
     *
     * @return array the loan details
     * @throws ServiceException
     */
    public function getLoan( $endpoint ) {

        $schedule = [];
        $loan     = [];
        $client   = $this->_container
            ->get( ServiceConstant::HTTP_SERVICE );

        try {

            $result = $client->get( $endpoint );

        }catch ( \Exception $e ) {

            $logger = $this->_container
                ->get( ServiceConstant::LOG_SERVICE );
            $message = $e->getMessage();
            $logger->error( $message, $context = [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'called_by' => 'LoanService::getLoan'
            ] );

            throw new ServiceException( $message );

        }

        if ( !empty( $result['loans'][0] ) ) {
            $loan     = array_merge(
                $result['loans'][0],
                $result['loans'][0]['terms']
            );
            $schedule = $this->_getRepaymentSchedule( $loan );
        }

        return [
            'loan'      => $loan,
            'schedule'  => $schedule
        ];

    }

    /**
     * Gets the repayment schedule of a loan
     *
     * Builds out a loan repayment schedule, and stores
     * it into a database table
     *
     * @param array $loan the loan details
     *
     * @return array the repayment schedule
     */
    private function _getRepaymentSchedule( array $loan ) {

        $borrower_model = $this->_container->get( ModelConstant::BORROWER_SCHEDULES );
        $schedule       = $borrower_model->findByLoanId( $loan['id'] );

        if ( $schedule ) {
            return $schedule;
        }

        $schedule = $this->_container
            ->get( ServiceConstant::SCHEDULER_SERVICE )
            ->computeBorrowerSchedule( $loan['loan_amount'], $loan['disbursal_date'], $loan['repayment_term'] );

            $is_saved = $this->_container
            ->get( ModelConstant::LOANS )
            ->save( [
            'loan_id'           => $loan['id'],
            'loan_amount'       => $loan['loan_amount'],
            'total_lenders'     => $loan['lender_count'],
            'repayment_months'  => $loan['repayment_term']
        ] );

        if ( $is_saved ) {

            $schedule_batch = [];

            foreach ( $schedule as $payment ) {

                $schedule_batch[] = [
                    'loan_id'           => $loan['id'],
                    'payment_date'      => $payment['payment_date'],
                    'payment_amount'    => $payment['payment_amount']
                ];

            }

            try {
                $borrower_model->batchSave( $schedule_batch );

            }catch ( \Exception $e ) {
                echo $e->getMessage();
                exit;
            }

        }

        return $schedule;

    }

}
