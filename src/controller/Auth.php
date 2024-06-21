<?php
require_once("RestController.php");
require_once("model/Test.php");

class Auth extends RestController
{
    protected function init()
    {
        $this->_name = "Auth";
        parent::init();
    }

    public function Test(Request $request)
    {
        global $connection;
        if(!$request->isPOST())
            $this->wrongMethod();
        $test = new Test($connection) ;
        //$arr = ["sql" => $test->GetTest()];
        $rows = $test->list();
        $c = count($rows);
        $arr = [$rows[0], $c];
        $this->returnResponse($arr);
    }
}