<?php
class Request
{
    private $_array = array();
    private $_method = "GET";

    private function parseArray($array)
    {
        foreach ($array as $key => $value) {
            $this->_array[$key] = $value;
        }
    }
    public function __construct($request)
    {
        if(isset($request))
        {
            $this->parseArray($request);
        }
        $this->_method = $_SERVER["REQUEST_METHOD"];
    }

    public function addJSON($data)
    {
        $request = json_decode($data, true);
        $this->parseArray($request);
    }

    public function __get($name)
    {
        if(!array_key_exists($name, $this->_array))
            return null;
        return $this->_array[$name];
    }

    public function __set($name, $value)
    {
        $this->_array[$name] = $value;
    }

    public function countElements()
    {
        return count($this->_array);
    }

    public function isGET():bool
    {
        return ($this->_method == "GET");
    }

    public function isPOST():bool
    {
        return ($this->_method == "POST");
    }
    public function isPUT():bool
    {
        return ($this->_method == "PUT");
    }
    public function isDELETE():bool
    {
        return ($this->_method == "DELETE");
    }

}
?>
