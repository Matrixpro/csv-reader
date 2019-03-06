MatrixPro CSV Reader
=====================
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](https://tldrlegal.com/license/mit-license)

Reads CSV files and returns an array containing all or some of the CSV rows. For large CSV files you may specify how many rows to work with at a time and where to start via the file pointer position. Compatible with large CSV datasets (tested on 4gb+ files).

Install via composer:

```
composer require matrixpro/csv-reader
```

Usage
-----
Pass a CSV file handle to the reader and use the getRows() method.

```php
$handle = fopen('path/to/file.csv', "r");
$reader = new CsvReader($handle);
$rows = $reader->getRows();
```

#### Get First Five Rows

Optionally save the position so you can process some rows and pick it up again later.

```php
$first_five_rows = $reader->getRows(5);
$csv_position = $reader->getPosition();
```

#### Get Next Five Rows

Use the position saved earlier to continue processing the file.

```php
$second_five_rows = $reader->getRows(5, $csv_position);
```

		
		