<?php
class Route{
    private $_route = [
        "/cards/registration"   => "Cards/Registration",
        "/cards/verify"         => "Cards/Verify",
        "/cards/reactivation"   => "Cards/Reactivate",
        "/cards/write-off"      => "Cards/Write_off"
    ];

    private $_paramRoute = [
        "/cards/info/*"     => "Cards/Info",
        "/cards/balance/*"  => "Cards/Balance",
        "/cards/*"          => "Cards/Delete"
    ];

    private function getValidRoute($route)
    {
        if(strpos($route,'?')){
            $param = explode("?", $route);
            return $param[0];}
        else return $route;
    }

    private function createRegEx($route)
    {
        $new_route = str_replace("/","\/", $route, $count);
        $new_route = str_replace("*","\w*((?!\/).)\w*$",$new_route);
        return "/" . $new_route . "/";
    }

    public function __init__(){}

    public function hasRoute($route)
    {
        return isset($this->_route[$this->getValidRoute($route)]);
    }

    public function hasParamRoute($route)
    {
        if(isset($this->_paramRoute))
        {
            foreach($this->_paramRoute as $key => $item)
            {
                if (preg_match($this->createRegEx($key), $route))
                {
                    return true;
                }
            }
        }
        return false;
    }

    public function getParamRoute($route)
    {
        foreach($this->_paramRoute as $key => $item)
        {
            if (preg_match($this->createRegEx($key), $route))
            {
                return $item;
            }
        }
    }

    public function getRoute($route)
    {
        return $this->_route[$this->getValidRoute($route)];
    }
}
?>