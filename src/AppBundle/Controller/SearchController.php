<?php

namespace AppBundle\Controller;

use AppBundle\AppController;
use AppBundle\Constants\ConfigConstant;
use AppBundle\Constants\ServiceConstant;
use AppBundle\Constants\ViewConstant;
use AppBundle\Exception\ControllerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AppController
{

    const MAX_QUERY_LENGTH = 60;

    /**
     * Returns the search results
     *
     * If its an AJAX request the function returns html formatted list of
     * loans as a json response. However, if its a regular http request, the functions returns
     * the homepage with the search results included
     *
     * @param Request $request the request object
     * @return Response an Http response object
     */
    public function indexAction( Request $request )
    {

        $endpoint = $this->_getSearchEndpoint( $request );
        $cache    = $this->get( ServiceConstant::CACHE_SERVICE );
        $response = $cache->get( $endpoint );

        if ( !$response ) {

            $search   = $this->get( ServiceConstant::SEARCH_SERVICE );
            $response = $search->query( $endpoint );

            if ( $response ) {
                $cache->set( $endpoint, $response );
            }

        }

        if ( $request->isXmlHttpRequest() ) {
            $response['loans'] = $this->renderView( ViewConstant::LOANS_ELEMENT, $response );
            return $this->_ajaxResponse( $response );
        }

        return $this->render( ViewConstant::HOME_PAGE, $response );

    }

    /**
     * Loads the configured url from parameters.yml
     *
     * @param Request $request the request object
     * @return string the url
     * @throws ControllerException if the url is not configured in parameters.yml
     */
    private function _getSearchEndpoint( Request $request ) {

        $config = $this->getParameter( ConfigConstant::KIVA_API );

        if ( empty( $config[ 'search' ] ) ) {

            $message = 'Missing kiva_api [search] parameter in parameters.yml';
            $logger  = $this->get( ServiceConstant::LOG_SERVICE );
            $logger->error( $message );
            throw new ControllerException( $message );

        }

        $endpoint = $config[ 'search' ];
        $params   = [
            'status' => 'funded',
            'page'   => 1
        ];

        $query = $request->get( 'q' );

        if ( $query ) {
            $query       = strtolower( trim( $query ) );
            $params['q'] = substr( $query, 0, self::MAX_QUERY_LENGTH );
        }

        $page = $request->get( 'page' );

        if ( ( $page ) && is_numeric( $page ) ) {
            $params['page'] = (int)$page;
        }

        asort( $params );

        return $endpoint . '?' . http_build_query( $params );

    }

}
