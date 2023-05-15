<?php
require_once("Controller.php");
require_once("Restfull.php");
class RestController extends Controller
{
    public function __construct()
    {
        header('Content-Type: application/json; charset=utf-8');
        parent::__construct();
    }

    protected final function returnResponse($response)
    {
        if($response)
            echo json_encode($response);
    }
}