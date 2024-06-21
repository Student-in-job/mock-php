<?php
require_once("RestController.php");
require_once("model/Test.php");

class CurlTest extends RestController
{
    protected function init()
    {
        $this->_name = "CurlTest";
        parent::init();
    }

    public function GetFromUrl(Request $request)
    {
        global $connection;
        if (!$request->isGET())
            $this->wrongMethod();
        $cURLConnection = curl_init();

        $val = $request->url;
        curl_setopt($cURLConnection, CURLOPT_URL, $val);
        curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($cURLConnection, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $data= curl_exec($cURLConnection);
        curl_close($cURLConnection);

        $arr = ["data", $data];
        $this->returnResponse($arr);
    }
}