<?php
class Controller
{
    protected $_name;

    protected function init(){}

    public function __construct()
    {
        $this->init();
    }

    public function getName($param = ""){
        return $this->_name;
    }

    protected final function returnView($response){
        echo $response;
    }

    protected function wrongMethod()
    {
        header('HTTP/1.1 405 Method Not Allowed');
        exit();
    }

    protected function badRequest()
    {
        header('HTTP/1.1 400 Bad Request');
        exit();
    }
}
?>