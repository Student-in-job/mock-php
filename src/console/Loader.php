<?php
class Loader{
    private array $_records;
    public function __construct($source_name, $skip = 0)
    {
        if (!file_exists($source_name))
            print("Can't find a file: " . $source_name . "\n");
        else {
            $file = fopen($source_name, 'r');
            $row = 0;
            while(($data = fgetcsv($file, 0, ';')) !== False){
                if (!isset($this->_records))
                    $this->_records = array();
                if ($row < $skip)
                {
                    $row++;
                    continue;
                }
                $row++;
                $this->_records[] = $data;
            }
        }
    }

    public function hasData():bool
    {
        return isset($this->_records);
    }

    public function Count():int
    {
        if (isset($this->_records))
            return count($this->_records);
        else
            return 0;
    }

    public function getData():array
    {
        return $this->_records;
    }

    static function makeFloatFromString($value):float
    {
        $str = str_replace(',','.', $value);
        return  floatval($str);
    }
}