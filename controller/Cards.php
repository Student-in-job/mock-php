<?php
require_once("RestController.php");
require_once("helper.php");
class Cards extends RestController
{
    protected function init()
    {
        $this->_name = "Cards";
        parent::init();
    }

    public function Registration($request)
    {
        if($_SERVER["REQUEST_METHOD"] != "POST")
            $this->wrongMethod();
        $regCode = helper::GenerateUNIQUE($request->pan);
        $arr = ["registrationCode" => $regCode];
        $this->returnResponse($arr);
    }

    public function Verify($request)
    {
        if($_SERVER["REQUEST_METHOD"] != "POST")
            $this->wrongMethod();
        $token = substr(helper::GenerateUNIQUE($request->registrationCode), 0 , 26);
        $arr = ["token" => $token, "bean" => "UMaAIKKIkknjWEXJUfPxxQHeWKEJ", "processingType" => "UZCARD"];
        $this->returnResponse($arr);
    }

    public function Info($token)
    {
        if($_SERVER['REQUEST_METHOD'] != "GET")
            $this->wrongMethod();
        $arr = [
            "bean" => "QIn",
            "bankId" => "yIvpRgmgQsYEKk",
            "expiry" => "AAAryjCRhLTuhnTodUewZQqaZErU",
            "currency" => "aofGvthLoyPLDADYzx",
            "state" => "STOLEN",
            "panMasked" => "WoaMAzEEplqjJ",
            "phoneNumber" => "jNBgpTmxx",
            "holderFullName" => "pIoQM",
            "hasSmsNotification" => true,
            "cardHolderType" => "INSTALLMENT",
            "processingType" => "UZCARD",
            "processingToken" => "ODRhfGEfX",
            "infoNotFound" => false
        ];
        $this->returnResponse($arr);
    }

    public function Balance($token)
    {
        if($_SERVER['REQUEST_METHOD'] != "GET")
            $this->wrongMethod();
        $arr = [
            "balance" => 958000,
            "state" => "UNKNOWN",
            "currency" => "YdvDhtAsLghPXAgtbprXPZkhnfLTBSX",
            "processingType" => "UZCARD"
        ];
        $this->returnResponse($arr);
    }

    public function Delete($token)
    {
        if($_SERVER['REQUEST_METHOD'] != "DELETE")
            $this->wrongMethod();
        $arr = null;
        header('HTTP/1.1 204 No Content');
        $this->returnResponse($arr);
    }

    public function Reactivate($request)
    {
        if($_SERVER["REQUEST_METHOD"] != "POST")
            $this->wrongMethod();
        $regCode = helper::GenerateUNIQUE($request->phoneNumber);
        $arr = ["registrationCode" => $regCode];
        $this->returnResponse($arr);
    }

    public function Write_off($request)
    {
        if($_SERVER["REQUEST_METHOD"] != "POST")
            $this->wrongMethod();
        $arr = null;
        $this->returnResponse($arr);
    }
}