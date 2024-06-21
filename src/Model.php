<?php
require_once("IQuery.php");
require_once("DBConnection.php");
class Model implements IQuery
{
    protected string $_name;
    protected array  $_fields;
    protected array $_conditions;
    protected DBConnection $_connection;
    /**
     * @param DBConnection $connection Database connection
     */
    public function __construct(DBConnection $connection = null)
    {
        $this->_connection = $connection;
        $this->_init();
    }

    protected function _init():void {}
    /**
     * @param DBConnection $connection Connection to database
     */
    public function setConnection(DBConnection $connection): void
    {
        $this->_connection = $connection;
    }

    /**
     * @param string $query SQL Query which need to be executed
     * @param bool $transactional True if no need to automatically wrap into BEGIN TRANSACTION... END TRANSACTION...
     * @return void
     */
    public function ExecuteQuery(string $query, bool $transactional = false): void
    {
        $this->_connection->executeQuery($query, $transactional);
    }

    /**
     * @param string $query SQL Query which need to be executed
     * @return array Data returned from Query
     */
    public function GetData(string $query): array
    {
        $result = $this->_connection->ExecuteQuery($query, null, true);
        return $result->fetchAll();
    }

    public function list():array
    {
        $query = $this->GenerateSelect();
        return $this->GetData($query);
    }

    protected function GenerateSelect(): string
    {
        $fields = "";
        if(isset($this->_fields))
        {
            foreach ($this->_fields as  $item)
            {
                $fields .=  $item  .  ', ';
            }
            $fields = rtrim($fields, ', ');
        }
        if($fields ===  "")  $fields = "*";
        //if()
        $result = "SELECT " . $fields .  " FROM " . $this->_name ;
        //$result .= . "WHERE " . $this->_conditions;
        return $result;
    }
}