<?php
require_once("RestController.php");
class RestfullController extends RestController implements Restfull
{
    public function __construct()
    {
        header('Content-Type: application/json; charset=utf-8');
        parent::__construct();
    }

    public function call($request, $method, $id = null){
        if($method == "GET"){
            if(!$id)
                return $this->_getById($id);
            else
                return $this->_list();
        }
        if($method == "POST"){
            return  $this->_create($request);
        }
        if($method == "PUT"){
            return $this->_modify($request);
        }
        if($method == "DELETE"){
            return $this->_delete($id);
        }
    }

    public function _list(){}
    public function _create($body){}
    public function _modify($body){}
    public function _delete($id){}
    public function _getById($id){}
}