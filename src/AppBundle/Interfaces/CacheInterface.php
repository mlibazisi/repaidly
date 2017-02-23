<?php

namespace AppBundle\Interfaces;

Interface CacheInterface
{

    /**
     * Get cached contents
     *
     * @param string $key an identifier for the cached contents
     * @return mixed the cached contents
     */
    public function get( $key );

    /**
     * Save contents into a cache
     *
     * @param string $key an identifier for the cached contents
     * @param mixed $value the contents to cache
     * @param int $expire cache expiration
     * @return void
     */
    public function set( $key, $value, $expire = null );

}
