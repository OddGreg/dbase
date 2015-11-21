<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace org\majkel\dbase;

use org\majkel\dbase\tests\utils\TestBase;

/**
 * Integration tests of dBase III Format
 *
 * @author majkel
 */
class DBase3Test extends TestBase {

    /**
     * @medium
     * @coversNothing
     */
    public function testReadDbase3() {
        $dbf = new Table('tests/fixtures/dBase3.dbf');
        self::assertSame($dbf->getRecordsCount(), 6);
        $record = $dbf->getRecord(0);
        self::assertSame('4', $record->SL_CHPODPL);
        self::assertSame('Bezp', $record->CHP_ODPLAT);
        self::assertSame(22, $record->NUM);
        self::assertSame('2015-06-26', $record->DAT->format('Y-m-d'));
        self::assertSame(true, $record['LOGIC']);
        self::assertSame('memo1', $record->MEMO);
    }

    /**
     * @medium
     * @coversNothing
     */
    public function testReadLongFile() {
        $results = [];
        $dbf = new Table('tests/fixtures/producents.dbf');
        foreach ($dbf as $index => $record) {
            $results[$index] = $record->SL_PROD;
        }
        self::assertCount(7356, $results);
    }

    /**
     * @test
     * @medium
     * @coversNothing
     */
    public function testcCopyRecords() {
        $sourceFile = 'tests/fixtures/dBase3.dbf';
        $destFile = 'tests/fixtures/dBase3.dbf.copy';

        if (file_exists($destFile)) {
            unlink($destFile);
        }
        copy($sourceFile, $destFile);

        $source = new Table($sourceFile);
        $dest = new Table($destFile, Table::MODE_READWRITE);

        foreach ($source as $sourceRecord) {
            $dest->insert($sourceRecord);
        }
    }

}
