<?php

namespace AppBundle;

use AppBundle\Interfaces\DatabaseInterface;

abstract class AppModel
{

    protected $_db;

    public function __construct( DatabaseInterface $db_engine ) {

        $this->_db = $db_engine;

    }

    /**
     * Save a record
     *
     * @param array $data the data to save
     * @return bool true on success, false on fail
     */
    public function save( array $data ) {

        return $this->_db->save( $this->_getTableName(), $data  );

    }

    /**
     * Query the db using sql
     *
     * @param string $sql the query
     * @param array $vars
     * @return array the results
     */
    public function fetchAll( $sql, $vars = [] ) {

        return $this->_db->fetchAll( $sql, $vars  );

    }

    /**
     * Save multiple records into the same table
     *
     * @param array $batch the record batch to save
     * @return bool true on success, false on fail
     */
    public function batchSave( array $batch ) {

        return $this->_db
            ->batchSave( $this->_getTableName(), $batch  );

    }

    abstract protected function _getTableName();

}
