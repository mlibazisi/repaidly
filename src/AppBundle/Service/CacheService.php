<?php

namespace AppBundle\Service;

use AppBundle\Constants\ConfigConstant;
use AppBundle\Constants\ServiceConstant;
use AppBundle\Exception\ServiceException;
use AppBundle\Interfaces\CacheInterface;
use Symfony\Component\DependencyInjection\Container;

class CacheService implements CacheInterface
{

    private $_cache;
    private $_container;

    /**
     * Instantiates the service state
     *
     * @param CacheInterface $cache inject the cache
     * @param Container $container the symfony service container
     */
    public function __construct( CacheInterface $cache, Container $container ) {

        $this->_cache     = $cache;
        $this->_container = $container;

    }

    /**
     * Get cached contents
     *
     * @param string $key an identifier for the cached contents
     * @return mixed the cached contents
     * @throws ServiceException
     */
    public function get( $key ) {

        $caching = $this->_container
            ->getParameter( ConfigConstant::CACHING );

        if ( isset( $caching['enabled'] ) && ( $caching['enabled'] == true ) ) {

            $key = $this->_formatKey( $key );

            try {

                return $this->_cache->get( $key );

            }catch ( \Exception $e ) {

                $logger = $this->_container
                    ->get( ServiceConstant::LOG_SERVICE );
                $message = $e->getMessage();
                $logger->error( $message, $context = [
                    'file'      => $e->getFile(),
                    'line'      => $e->getLine(),
                    'called_by' => 'CacheService::get'
                ] );

                throw new ServiceException( $message );

            }

        }

        return null;

    }

    /**
     * Save contents into a cache
     *
     * @param string $key an identifier for the cached contents
     * @param mixed $value the contents to cache
     * @param int $expire cache expiration
     * @return void
     * @throws ServiceException
     */
    public function set( $key, $value, $expire = null  ) {

        $caching = $this->_container
            ->getParameter( ConfigConstant::CACHING );

        if ( !isset( $caching['enabled'] )
            || ( $caching['enabled'] != true ) ) {
            return;
        }

        if ( isset( $caching['expire'] ) ) {
            $expire = $caching['expire'];
        }

        $key = $this->_formatKey( $key );

        try {

            $this->_cache->set( $key, $value, $expire );

        }catch ( \Exception $e ) {

            $logger = $this->_container
                ->get( ServiceConstant::LOG_SERVICE );
            $message = $e->getMessage();
            $logger->error( $message, $context = [
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'called_by' => 'CacheService::set'
            ] );

            throw new ServiceException( $message );

        }

    }

    /**
     * a simple way to format the cache key
     *
     * @param string $key an identifier for the cached contents
     * @return string an md5 hash of the key
     */
    private function _formatKey( $key ) {

        return md5( $key );

    }

}
