<?php

namespace AppBundle\Tests\Service;

use AppBundle\Service\SchedulerService;

class SchedulerServiceTest extends \PHPUnit_Framework_TestCase
{

    public function testComputeBorrowerSchedule()
    {

        $loan_amount    = 250;
        $disbursal_date = '01/27/2017';
        $term           = 8;
        $schedule       = [
            [
                'payment_date'   => '2017-02-27',
                'payment_amount' => 32.00
            ],
            [
                'payment_date'   => '2017-03-27',
                'payment_amount' => 32.00
            ],
            [
                'payment_date'   => '2017-04-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-05-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-06-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-07-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-08-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-09-27',
                'payment_amount' => 31.00
            ]
        ];

        $scheduler = new SchedulerService();
        $computed  = $scheduler->computeBorrowerSchedule( $loan_amount, $disbursal_date, $term );
        $this->assertEquals( $schedule, $computed );

    }

    public function testComputeLenderSchedule()
    {

        $total_lenders      = 4;
        $borrower_schedule  = [
            [
                'payment_date'   => '2017-02-27',
                'payment_amount' => 32.00
            ],
            [
                'payment_date'   => '2017-03-27',
                'payment_amount' => 32.00
            ],
            [
                'payment_date'   => '2017-04-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-05-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-06-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-07-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-08-27',
                'payment_amount' => 31.00
            ],
            [
                'payment_date'   => '2017-09-27',
                'payment_amount' => 31.00
            ]
        ];

        $lender_schedule = [
            [
                'receipt_date'   => '2017-03-27',
                'receipt_amount' => 8.00
            ],
            [
                'receipt_date'   => '2017-04-27',
                'receipt_amount' => 8.00
            ],
            [
                'receipt_date'   => '2017-05-27',
                'receipt_amount' => 7.75
            ],
            [
                'receipt_date'   => '2017-06-27',
                'receipt_amount' => 7.75
            ],
            [
                'receipt_date'   => '2017-07-27',
                'receipt_amount' => 7.75
            ],
            [
                'receipt_date'   => '2017-08-27',
                'receipt_amount' => 7.75
            ],
            [
                'receipt_date'   => '2017-09-27',
                'receipt_amount' => 7.75
            ],
            [
                'receipt_date'   => '2017-10-27',
                'receipt_amount' => 7.75
            ]
        ];

        $scheduler = new SchedulerService();
        $computed  = $scheduler->computeLenderSchedule( $borrower_schedule, $total_lenders );
        $this->assertEquals( $lender_schedule, $computed );

    }

}