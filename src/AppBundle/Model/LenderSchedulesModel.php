<?php

namespace AppBundle\Model;

use AppBundle\AppModel;

class LenderSchedulesModel extends AppModel
{

    const TABLE_NAME = 'lender_schedules';

    public function findAll( $loan_id, $lender_id ) {

        return $this->_db->find(
            $this->_getTableName(),[
            'loan_id'   => $loan_id,
            'lender_id' => $lender_id
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
