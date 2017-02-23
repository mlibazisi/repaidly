<?php

namespace AppBundle\Adapter;

use AppBundle\Interfaces\DatabaseInterface;
use Doctrine\DBAL\Connection;

class DoctrineAdapter implements DatabaseInterface
{

    private $_doctrine;

    public function __construct( Connection $doctrine ) {

        $this->_doctrine = $doctrine;

    }

    public function save( $table_name, array $data ) {

        return $this->_doctrine->insert( $table_name, $data );

    }

    public function batchSave( $table_name, array $batch ) {

        foreach ( $batch as $record ) {
            $this->save( $table_name, $record );
        }

    }

    public function fetchAll( $sql, $vars = [] ) {

        return $this->_doctrine
            ->fetchAll( $sql, $vars );

    }

    public function find( $table_name, array $conditions = [], array $fields = [], $limit = 25 ) {

        $fields = empty( $fields ) ? '*' : implode( ',', $fields );

        if ( is_numeric( $limit ) ) {
            $limit = ' LIMIT ' . trim($limit);
        } else {
            $limit = '';
        }

        $where = $and = '';
        $vars  = [];

        if ( $conditions ) {

            $where = ' WHERE ';

            foreach ( $conditions as $field => $value ) {

                $where .= "{$and}{$field}=?";
                $vars[] = $value;
                $and    = ' AND ';

            }

        }

        return $this->fetchAll( "SELECT {$fields} FROM {$table_name}{$where}{$limit}", $vars );

    }

    public function lastInsertId() {

        return $this->_doctrine->lastInsertId();

    }

}
