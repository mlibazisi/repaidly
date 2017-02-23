<?php

namespace AppBundle\Model;

use AppBundle\AppModel;
use Doctrine\ORM\Query;

class BorrowerSchedulesModel extends AppModel
{

    const TABLE_NAME = 'borrower_schedules';

    public function findByLoanId( $loan_id ) {

        $loans_table    = LoansModel::TABLE_NAME;
        $borrower_table = $this->_getTableName();
        $sql            = <<<EOD
SELECT *
FROM
    {$borrower_table}
    INNER JOIN
    {$loans_table}
    ON {$borrower_table}.loan_id={$loans_table}.loan_id
WHERE
    {$loans_table}.loan_id={$loan_id}
EOD;

        return $this->fetchAll( $sql );

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
