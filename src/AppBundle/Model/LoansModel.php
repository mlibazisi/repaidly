<?php

namespace AppBundle\Model;

use AppBundle\AppModel;

class LoansModel extends AppModel
{

    const TABLE_NAME = 'loans';

    /**
     * Find a loan by its id
     *
     * @param string $loan_id the kiva id
     * @return array the query results
     */
    public function findByLoanId( $loan_id ) {

        return $this->_db->find(
            $this->_getTableName(),[
            'loan_id' => $loan_id
        ] );

    }

    /**
     * Return the database table name
     *
     * @return string the table name
     */
     protected function _getTableName() {

        return self::TABLE_NAME;

    }

}
