<?php

namespace AppBundle\Controller;

use AppBundle\AppController;
use AppBundle\Constants\ConfigConstant;
use AppBundle\Constants\ServiceConstant;
use AppBundle\Constants\ViewConstant;
use AppBundle\Exception\ControllerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LenderController extends AppController
{

    /**
     * Loads the lender page
     *
     * If a lender_id is not specified, all
     * lenders to this loan will be returned
     *
     * @param string $loan_id the id of the loan
     * @param string $lender_id the lender id
     *
     * @return Response an Http response object
     * @throws ControllerException
     */
    public function indexAction( Request $request, $loan_id, $lender_id = null )
    {

        if ( !$request->isXmlHttpRequest() ) {
            throw new ControllerException( 'Only AJAX requests allowed' );
        }

        if ( $lender_id ) {

            $schedule = $this->_getLenderSchedule( $loan_id, $lender_id );
            return $this->render( ViewConstant::LENDERS_SCHEDULE_ELEMENT, [
                'schedule' => $schedule
            ] );

        }

        $params              = $request->query->all();
        $response            = $this->_getLendersByLoanId( $loan_id, $params );
        $response['lenders'] = $this->renderView( ViewConstant::LENDERS_ELEMENT, $response );

        return $this->_ajaxResponse( $response );

    }

    private function _getLendersByLoanId( $loan_id, array $params ) {

        $endpoint = $this->_getLendersEndpoint( $loan_id, $params );
        $cache    = $this->get( ServiceConstant::CACHE_SERVICE );
        $lenders  = $cache->get( $endpoint );

        if ( !$lenders ) {

            $lenders = $this->get( ServiceConstant::LENDER_SERVICE )
                ->findAll( $endpoint );

            if ( !empty( $lenders['lenders'] ) ) {

                foreach ( $lenders['lenders'] as $index => $lender ) {

                    $lenders['lenders'][ $index ]['loan_id'] = $loan_id;

                    //anonymous users
                    if ( empty( $lender['lender_id'] ) ) {
                        $lenders['lenders'][ $index ]['is_anonymous'] = true;
                    }

                }

                $cache->set( $endpoint, $lenders );

            }

        }

        return $lenders;

    }

    private function _getLenderSchedule( $loan_id, $lender_id ) {

        $lender_key = md5( 'LENDER_' . $lender_id . '_LOAN_' . $loan_id );
        $cache      = $this->get( ServiceConstant::CACHE_SERVICE );
        $schedule   = $cache->get( $lender_key );

        if ( !$schedule ) {

            $schedule = $this->get( ServiceConstant::LENDER_SERVICE )
                ->getSchedule( $loan_id, $lender_id );

            if ( !empty( $schedule ) ) {
                $cache->set( $lender_key, $schedule );
            }

        }

        return $schedule;

    }

    private function _getLendersEndpoint( $loan_id, $params ) {

        $config = $this->getParameter( ConfigConstant::KIVA_API );
        $page   = 1;

        if ( empty( $config[ 'lenders' ] ) ) {

            $message = 'Missing kiva_api [lenders] parameter in parameters.yml';
            $logger  = $this->get( ServiceConstant::LOG_SERVICE );
            $logger->error( $message );
            throw new ControllerException( $message );

        }

        if ( !empty( $params['page'] )
            && is_numeric( $params['page'] ) ) {
            $page = $params['page'];
        }

        return str_replace( '{loan_id}', $loan_id, $config[ 'lenders' ] ) . '?page=' . $page;

    }

}
