<?php

namespace AppBundle\Service;

use AppBundle\Constants\ServiceConstant;
use AppBundle\Exception\ServiceException;
use AppBundle\Interfaces\SearchInterface;
use Symfony\Component\DependencyInjection\Container;

class SearchService implements SearchInterface
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
     * Returns the search results
     *
     * @param string $endpoint the url to query
     * @return array an array of the search results
     * @throws ServiceException
     */
    public function query( $endpoint ) {

        $response = [
            'loans'   => [],
            'paging'  => [
                'page'      => 0,
                'total'     => 0,
                'page_size' => 0,
                'pages'     => 0
            ]
        ];

        $client = $this->_container->get( ServiceConstant::HTTP_SERVICE );

        try {

            $result = $client->get( $endpoint );

        }catch ( \Exception $e ) {

            $logger = $this->_container
                ->get( ServiceConstant::LOG_SERVICE );
            $message = $e->getMessage();
            $logger->error( $message, $context = [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'called_by' => 'SearchService::query'
            ] );

            throw new ServiceException( $message );

        }

        if ( ( !empty( $result['loans'] ) && is_array( $result['loans'] ) )
            && ( !empty( $result['paging'] ) && is_array( $result['paging'] ) ) ) {

            $response['loans'] = $result['loans'];

            foreach ( $response['paging'] as $key => $val ) {

                if ( isset( $result['paging'][ $key ] ) ) {
                    $response['paging'][ $key ] = $result['paging'][ $key ];
                }

            }

        }

        return $response;

    }

}
