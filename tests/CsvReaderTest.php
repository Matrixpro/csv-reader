<?php

use MatrixPro\CsvReader\CsvReader;
use MatrixPro\CsvDelimiterFinder\CsvDelimiterFinder;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase
{
	public function test_it_can_detect_delimiters()
	{
		$reader = new CsvReader(dirname(__FILE__).'/CsvTestFile1.csv');
		
		$this->assertSame(',', $reader->getDelimiter());
		
		$reader = new CsvReader(dirname(__FILE__).'/CsvTestFile2.csv');
		
		$this->assertSame(';', $reader->getDelimiter());
	}
	
	
	public function test_it_can_use_position_to_skip_rows()
	{
		$reader = new CsvReader(dirname(__FILE__).'/CsvTestFile1.csv');
		
		$first_five_rows = $reader->getRows(5);
		
		$this->assertEquals('5', count($first_five_rows));
		
		$csv_position = $reader->getPosition();
		
		$second_five_rows = $reader->getRows(5, $csv_position);
		
		$this->assertEquals('5', count($second_five_rows));
		
		$this->assertArrayHasKey('street',$second_five_rows[0]);
		
		$this->assertEquals('5828 PEPPERMILL CT', $second_five_rows[0]['street']);
	}
	
	public function test_it_can_use_custom_delimiter()
	{
		$expected = [
			[
				'header1' => 'col1 test',
				'header2' => 'col2 test',
				'header3' => 'col3 test'
			],
			[
				'header1' => 'col1 test',
				'header2' => 'col2 test',
				'header3' => 'col3 test'
			],
			[
				'header1' => 'col1 test',
				'header2' => 'col2 test',
				'header3' => 'col3 test'
			],
			[
				'header1' => 'col1 test',
				'header2' => 'col2 test',
				'header3' => 'col3 test'
			]
		];
		
		$custom_delimiter = '^';
		
		$reader = new CsvReader(dirname(__FILE__).'/CsvTestFile5.csv', $custom_delimiter);
		
		$contents = $reader->getRows();
		
		$this->assertEquals('4', count($contents));
		
		$this->assertSame($expected, $contents);
	}
	
	public function test_it_can_put_all_csv_contents_to_array()
	{
		$expected = [
			[
				'header1' => 'col1 test',
				'header2' => 'col2 test',
				'header3' => 'col3 test'
			],
			[
				'header1' => 'col1 test',
				'header2' => 'col2 test',
				'header3' => 'col3 test'
			],
			[
				'header1' => 'col1 test',
				'header2' => 'col2 test',
				'header3' => 'col3 test'
			],
			[
				'header1' => 'col1 test',
				'header2' => 'col2 test',
				'header3' => 'col3 test'
			]
		];
		
		$reader = new CsvReader(dirname(__FILE__).'/CsvTestFile2.csv');
		
		$contents = $reader->getRows();
		
		$this->assertEquals('4', count($contents));
		
		$this->assertSame($expected, $contents);
	}
	
	/*
	Moderately large CSV datasets can be found here:
	https://files.digital.nhs.uk/publicationimport/pub17xxx/pub17356/gp-reg-patients-lsoa-alt-tall.csv
	*/
	
	// public function test_it_can_detect_delimiters_in_large_dataset()
	// {
	// 	$reader = new CsvReader(dirname(__FILE__).'/CsvTestFile3.csv');
		
	// 	$this->assertSame(',', $reader->getDelimiter());
	// }
	
	// public function test_it_can_use_position_to_skip_rows_in_large_dataset()
	// {
	// 	$reader = new CsvReader(dirname(__FILE__).'/CsvTestFile3.csv');
		
	// 	$first_five_rows = $reader->getRows(5);
		
	// 	$this->assertEquals('5', count($first_five_rows));
		
	// 	$csv_position = $reader->getPosition();
		
	// 	$second_five_rows = $reader->getRows(5, $csv_position);
		
	// 	$this->assertEquals('5', count($second_five_rows));
		
	// 	$this->assertArrayHasKey('LSOA_CODE',$second_five_rows[0]);
		
	// 	$this->assertEquals('E01012191', $second_five_rows[0]['LSOA_CODE']);
	// }
	
	/*
	Very large, 4gb+, CSV datasets (for example: 'United States Population Records') from here:
	https://factfinder.census.gov/faces/tableservices/jsf/pages/productview.xhtml?pid=ACS_pums_csv_2013_2017&prodType=document
	*/
	
	// public function test_it_can_detect_delimiters_in_very_large_dataset()
	// {
	// 	$reader = new CsvReader(dirname(__FILE__).'/CsvTestFile4.csv');
		
	// 	$this->assertSame(',', $reader->getDelimiter());
	// }
	
	// public function test_it_can_use_position_to_skip_rows_in_very_large_dataset()
	// {
	// 	$reader = new CsvReader(dirname(__FILE__).'/CsvTestFile4.csv');
		
	// 	$first_five_rows = $reader->getRows(5);
		
	// 	$this->assertEquals('5', count($first_five_rows));
		
	// 	$csv_position = $reader->getPosition();
		
	// 	$second_five_rows = $reader->getRows(5, $csv_position);
		
	// 	$this->assertEquals('5', count($second_five_rows));
		
	// 	$this->assertArrayHasKey('SERIALNO',$second_five_rows[0]);
		
	// 	$this->assertEquals('2013000000156', $second_five_rows[0]['SERIALNO']);
	// }
	
}