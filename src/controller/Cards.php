<?php
require_once("RestController.php");
require_once("helper.php");
require_once("Request.php");

class Cards extends RestController
{
    protected function init()
    {
        $this->_name = "Cards";
        parent::init();
    }

    public function Registration(Request $request)
    {
        if(!$request->isPOST())
            $this->wrongMethod();
        $regCode = helper::GenerateUNIQUE($request->pan);
        $arr = ["registrationCode" => $regCode];
        $this->returnResponse($arr);
    }

    public function Verify(Request $request)
    {
        if(!$request->isPOST())
            $this->wrongMethod();
        $token = substr(helper::GenerateUNIQUE($request->registrationCode), 0 , 26);
        $arr = ["token" => $token, "bean" => "UMaAIKKIkknjWEXJUfPxxQHeWKEJ", "processingType" => "UZCARD"];
        $this->returnResponse($arr);
    }

    public function Info(Request $request)
    {
        if(!$request->isGET())
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

    public function Balance(Request $request)
    {
        if(!$request->isGET())
            $this->wrongMethod();
        $arr = [
            "balance" => 958000,
            "state" => "UNKNOWN",
            "currency" => "YdvDhtAsLghPXAgtbprXPZkhnfLTBSX",
            "processingType" => "UZCARD"
        ];
        $this->returnResponse($arr);
    }

    public function Delete(Request $request)
    {
        if(!$request->isDELETE())
            $this->wrongMethod();
        $arr = null;
        header('HTTP/1.1 204 No Content');
        $this->returnResponse($arr);
    }

    public function Reactivate(Request $request)
    {
        if(!$request->isPOST())
            $this->wrongMethod();
        $regCode = helper::GenerateUNIQUE($request->phoneNumber);
        $arr = ["registrationCode" => $regCode];
        $this->returnResponse($arr);
    }

    public function Write_off(Request $request)
    {
        if(!$request->isPOST())
            $this->wrongMethod();
        $arr = null;
        $this->returnResponse($arr);
    }
}