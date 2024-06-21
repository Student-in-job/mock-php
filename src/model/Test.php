<?php
require_once ("Model.php");

class Test extends Model
{
    protected function _init(): void
    {
        $this->_name = "Test";
        $this->_fields = ["id", "name", "age", "bday"];
    }

    public function GetTest(): string
    {
       return $this->GenerateSelect();
    }

}