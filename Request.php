<?php
class Request
{
    private $_array = array();

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
}
?>
