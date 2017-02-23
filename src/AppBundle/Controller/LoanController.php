<?php

namespace AppBundle\Controller;

use AppBundle\Constants\ConfigConstant;
use AppBundle\Constants\ServiceConstant;
use AppBundle\Constants\ViewConstant;
use AppBundle\Exception\ControllerException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class LoanController extends Controller
{

    /**
     * Loads the loan page
     *
     * @return Response an Http response object
     */
    public function indexAction( $loan_id )
    {

        $endpoint = $this->_getLoanEndpoint( $loan_id );
        $cache    = $this->get( ServiceConstant::CACHE_SERVICE );
        $loan     = $cache->get( $endpoint );

        if ( !$loan ) {

            $loan_service   = $this->get( ServiceConstant::LOAN_SERVICE );
            $loan           = $loan_service->getLoan( $endpoint );

            if ( $loan ) {
                $cache->set( $endpoint, $loan );
            }

        }

        return $this->render( ViewConstant::LOAN_PAGE, $loan );

    }

    /**
     * Loads the configured url from parameters.yml
     *
     * @return string the url
     * @throws ControllerException if the url is not configured in parameters.yml
     */
    private function _getLoanEndpoint( $loan_id ) {

        $config = $this->getParameter( ConfigConstant::KIVA_API );

        if ( empty( $config[ 'loan' ] ) ) {

            $message = 'Missing kiva_api [loan] parameter in parameters.yml';
            $logger  = $this->get( ServiceConstant::LOG_SERVICE );
            $logger->error( $message );
            throw new ControllerException( $message );

        }

        return str_replace( '{loan_id}', $loan_id, $config[ 'loan' ] );

    }

}
