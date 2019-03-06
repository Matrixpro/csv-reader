<?php

namespace MatrixPro\CsvReader;

use MatrixPro\CsvReader\Exceptions\FileNotFoundException;
use MatrixPro\CsvReader\Exceptions\InvalidContentException;
use MatrixPro\CsvDelimiterFinder\CsvDelimiterFinder;

/**
 * Reads CSV files and returns an array containing all or some of the CSV rows.
 * For large CSV files you may specify how many rows to work with at a time and
 * where to start via the file pointer position. Compatible with large CSV
 * datasets (tested on 4gb+ files).
 */

class CsvReader {
    
    private $handle;
    private $parse_header;
    private $header;
    private $delimiter;
    private $length;
    private $position;
    private $max_lines;

    /**
     * Set up some required parameters
     *
     * @param      string   $file       The csv file to process
     * @param      string   $delimiter  The delimiter. Use 'auto' to auto-detect the delimiter, or set a custom one.
     * @param      integer  $length     The length should be greater than the longest line. Leave at zero for unlimited.
     *
     * @throws     \MatrixPro\CsvReader\Exceptions\FileNotFoundException  If the CSV file can't be found
     */
    function __construct($file, $delimiter='auto', $length=0)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException('CSV file not found!');
        }
        
        ini_set("auto_detect_line_endings", true);
        
        $this->handle = fopen($file, "r");
        $this->setDelimiter($delimiter);
        $this->delimiter = $this->getDelimiter();
        $this->length = $length;
        $this->setHeader();
    }
    
    
    /**
     * Sets the header row to use in assosiative array later.
     */
    private function setHeader()
    {
         rewind($this->handle);
         $this->headers = fgetcsv($this->handle, $this->length, $this->delimiter);
    }
    
    
    /**
     * Sets the delimiter.
     *
     * @param      string   $delimiter  The delimiter. Use 'auto' to auto-detect the delimiter, or set a custom one. 
     *
     * @throws     \MatrixPro\CsvReader\Exceptions\InvalidContentException  If delimiter auto-finder fails
     */
    public function setDelimiter($delimiter)
    {
        if ($delimiter == 'auto') {
            $finder = new CsvDelimiterFinder($this->handle);
            $this->delimiter = $finder->findDelimiter();
        } else {
            $this->delimiter = $delimiter;
        }
        
        
        if ($this->delimiter === FALSE)
            throw new InvalidContentException('Unable to auto-determine delimiter.');
    }
    
    /**
     * Gets the delimiter.
     *
     * @return     <type>  The delimiter.
     */
    public function getDelimiter()
    {
        return $this->delimiter;
    }

    /**
     * Closes the file handle
     */
    function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }

    /**
     * Gets the csv rows.
     *
     * @param      integer  $max_lines   The number of CSV lines to process. Setting zero = unlimited lines.
     * @param      integer  $position    The file pointer position to start with
     * @param      string   $key_type  @TODO - The array key type: associative/index
     *
     * @throws     \MatrixPro\CsvReader\Exceptions\InvalidContentException  (description)
     *
     * @return     array The rows.
     */
    function getRows($max_lines=0, $position=0, $key_type='associative')
    {

        $data = array();

        if ($max_lines > 0)
            $line_count = 0;
        else
            $line_count = -1; // ignore line limiting

        if ($position) fseek($this->handle, $position);
        
        while ($line_count < $max_lines && ($row = fgetcsv($this->handle, $this->length, $this->delimiter)) !== FALSE) {
            
            foreach ($this->headers as $i => $header) {
                
                if (!isset($row[$i]))
                    throw new InvalidContentException('CSV data column count is different from header column count!');
                
                if ($key_type == 'associative') {
                    $row_new[$header] = $row[$i];
                } else {
                    $row_new[$i] = $row[$i];
                }
            }
            
            $data[] = $row_new;

            if ($max_lines > 0) 
                $line_count++;
        }
        
        if (count($data) <= 1) {
            throw new \InvalidContentException('Only one row found, possible error detecting new lines in CSV file. ');
        }
        
        return $data;
    
    }
    
    /**
     * Gets the position of the file pointer.
     *
     * @return     <type>  The position.
     */
    public function getPosition()
    {
        return ftell($this->handle);
    }
    
    /**
     * Gets the CSV headers.
     *
     * @return     array  The headers.
     */
    public function getHeader()
    {
        return $this->headers;
    }
    
    

} 

?>