<?php
require_once("DBConnection.php");
class Model
{
    protected DBConnection $_connection;
    public function __construct($dbconnection =  null)
    {
        $this->_connection = $dbconnection;
    }

    /**
     * @param DBConnection $connection Connection to database
     */
    public function setConnection(DBConnection $connection): void
    {
        $this->_connection = $connection;
    }
}