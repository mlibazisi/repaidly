<?php

namespace AppBundle\Adapter;

use AppBundle\Constants\ServiceConstant;
use AppBundle\Exception\AdapterException;
use AppBundle\Interfaces\CacheInterface;
use Symfony\Component\DependencyInjection\Container;

class MemcachedAdapter implements CacheInterface
{

    private $_container;
    private $_cache;

    public function __construct( Container $container ) {

        $this->_container = $container;

    }

    /**
     * Get cached contents
     *
     * @param string $key an identifier for the cached contents
     * @return mixed the cached contents
     */
    public function get( $key ) {

        return $this->_cache()->get( $key );

    }

    /**
     * Save contents into a cache
     *
     * @param string $key an identifier for the cached contents
     * @param mixed $value the contents to cache
     * @param int $expire cache expiration
     * @return void
     */
    public function set( $key, $value, $expire = null  ) {

        $this->_cache()->set( $key, $value, $expire );

    }

    /**
     * Inject the cache engine
     *
     * @param \Memcached $cache an instance of memcached
     */
    public function setCache( \Memcached $cache  ) {

        $this->_cache = $cache;

    }

    /**
     * Load memcached
     *
     * If memcached was not injected,
     * then try to manually load it
     *
     * @return \Memcached an instance of memcached
     * @throws AdapterException if missing configs
     */
    private function _cache() {

        if ( $this->_cache ) {
            return $this->_cache;
        }

        $config = $this->_container
            ->getParameter( ServiceConstant::MEMCACHED );

        if ( empty( $config['host'] )
            || empty( $config['port'] ) ) {
            throw new AdapterException( 'missing host and/or port in cache configuration' );
        }

        $memcachedd    = new \Memcached();
        $this->_cache = $memcachedd->addServer( $config['host'], $config['port'] );

    }

}
