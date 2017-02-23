<?php

namespace AppBundle\Service;

use AppBundle\Constants\ModelConstant;
use AppBundle\Constants\ServiceConstant;
use AppBundle\Exception\ServiceException;
use AppBundle\Interfaces\LenderInterface;
use Symfony\Component\DependencyInjection\Container;

class LenderService implements LenderInterface
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

    public function findAll( $endpoint ) {

        try {

            return $this->_container
                ->get( ServiceConstant::HTTP_SERVICE )
                ->get( $endpoint );

        }catch ( \Exception $e ) {

            $logger = $this->_container
                ->get( ServiceConstant::LOG_SERVICE );
            $message = $e->getMessage();
            $logger->error( $message, $context = [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'called_by' => 'LenderService::findAll'
            ] );

            throw new ServiceException( $message );

        }

    }

    public function getSchedule( $loan_id, $lender_id ) {

        $loan = $this->_container
            ->get( ModelConstant::LOANS )
            ->findByLoanId( $loan_id );

        if ( empty( $loan[0] ) ) {
            return [];
        }

        $loan          = $loan[0];
        $loan_schedule = $this->_container
            ->get( ModelConstant::BORROWER_SCHEDULES )
            ->findByLoanId( $loan_id );

        if ( !$loan_schedule ) {
            return [];
        }

        $lenders_model = $this->_container
            ->get( ModelConstant::LENDER_SCHEDULES );

        $schedule = $lenders_model->findAll( $loan_id, $lender_id );

        if ( $schedule ) {
            return $schedule;
        }

        $schedule = $this->_container
            ->get( ServiceConstant::SCHEDULER_SERVICE )
            ->computeLenderSchedule( $loan_schedule, $loan['total_lenders'] );

        foreach ( $schedule as $index => $payment ) {
            $schedule[ $index ]['lender_id'] = $lender_id;
            $schedule[ $index ]['loan_id']   = $loan_id;
        }

        $lenders_model->batchSave( $schedule );

        return $schedule;

    }

}
