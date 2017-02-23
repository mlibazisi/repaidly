<?php

namespace AppBundle\Interfaces;

Interface DatabaseInterface
{

    /**
     * Query the database
     *
     * @param string $table_name the database table to save to
     * @param array $conditions the search results
     * @param array $fields the fields to return
     * @param int $limit the limit
     *
     * @return array the query results
     */
    public function find( $table_name, array $conditions = [], array $fields = [], $limit = 25 );

    /**
     * Save into the database
     *
     * @param string $table_name the database table to save to
     * @param array $data the data to save as an assoc array
     * @return bool true on success, false on fail
     */
    public function save( $table_name, array $data );

    /**
     * Get the last insert id
     *
     * @return int the last insert id
     */
    public function lastInsertId();

    /**
     * Save multiple records to the same table at once
     *
     * @param string $table_name the database table to save to
     * @param array $batch the batched data
     * @return bool true on success, false on fail
     */
    public function batchSave( $table_name, array $batch );

    public function fetchAll( $sql, $vars = []  );

}
