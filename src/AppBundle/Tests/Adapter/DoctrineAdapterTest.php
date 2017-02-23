<?php

namespace AppBundle\Tests\Adapter;

use AppBundle\Adapter\DoctrineAdapter;

class DoctrineAdapterTest extends \PHPUnit_Framework_TestCase
{

    public function testSave()
    {

        $data = [
            'page'      => 1,
            'total'     => 36,
            'page_size' => 10,
            'pages'     => 742
        ];

        $table_name = 'test_table';
        $doctrine   = $this->getMockBuilder( 'Doctrine\DBAL\Connection' )
            ->disableOriginalConstructor()
            ->getMock();

        $doctrine->expects( $this->once() )
            ->method('insert')
            ->with(
                $this->equalTo( $table_name ),
                $this->equalTo( $data )
            )->will( $this->returnValue( true ) );

        $doctrineAdapter = new DoctrineAdapter( $doctrine );
        $result          = $doctrineAdapter->save( $table_name, $data  );

        $this->assertTrue( $result );

    }

    public function testlastInsertId()
    {

        $lastInsertId = 3;
        $doctrine     = $this->getMockBuilder( 'Doctrine\DBAL\Connection' )
            ->disableOriginalConstructor()
            ->getMock();

        $doctrine->expects( $this->once() )
            ->method('lastInsertId')
            ->will( $this->returnValue( $lastInsertId ) );

        $doctrineAdapter = new DoctrineAdapter( $doctrine );
        $result          = $doctrineAdapter->lastInsertId();

        $this->assertEquals( $lastInsertId, $result );

    }

    public function testFetchAll()
    {

        $sql        = 'SELECT * FROM TEST_TABLE';
        $records    = [ 'q', 'b', 'c' ];
        $doctrine   = $this->getMockBuilder( 'Doctrine\DBAL\Connection' )
            ->disableOriginalConstructor()
            ->getMock();

        $doctrine->expects( $this->once() )
            ->method('fetchAll')
            ->with(
                $this->equalTo( $sql ),
                $this->equalTo( [] )
            )->will( $this->returnValue( $records ) );

        $doctrineAdapter = new DoctrineAdapter( $doctrine );
        $result          = $doctrineAdapter->fetchAll( $sql  );

        $this->assertEquals( $records, $result );

    }

    public function testBatchSave()
    {

        $data = [
            [ 'id' => 3 ],
            [ 'id' => 4 ]
        ];

        $table_name = 'test_table';
        $doctrine   = $this->getMockBuilder( 'Doctrine\DBAL\Connection' )
            ->disableOriginalConstructor()
            ->getMock();

        $doctrine->expects( $this->at(0) )
            ->method('insert')
            ->with(
                $this->equalTo( $table_name ),
                $this->equalTo( $data[0] )
            )->will( $this->returnValue( true ) );

        $doctrine->expects( $this->at(1) )
            ->method('insert')
            ->with(
                $this->equalTo( $table_name ),
                $this->equalTo( $data[1] )
            )->will( $this->returnValue( true ) );

        $doctrineAdapter = new DoctrineAdapter( $doctrine );
        $doctrineAdapter->batchSave( $table_name, $data  );

    }

    public function testFind()
    {

        $table      = 'test_table';
        $conditions = [ 'id' => 3 ];
        $record     = 'record';
        $sql        = 'SELECT * FROM test_table WHERE id=? LIMIT 25';
        $doctrine   = $this->getMockBuilder( 'Doctrine\DBAL\Connection' )
            ->disableOriginalConstructor()
            ->getMock();

        $doctrine->expects( $this->once() )
            ->method('fetchAll')
            ->with(
                $this->equalTo( $sql ),
                $this->equalTo( [ 3 ] )
            )->will( $this->returnValue( $record ) );

        $doctrineAdapter = new DoctrineAdapter( $doctrine );
        $result          = $doctrineAdapter->find( $table, $conditions  );

        $this->assertEquals( $record, $result );

    }

}