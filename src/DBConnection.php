<?php

class DBConnection
{
    protected string $_host;
    protected string $_port;
    protected string $_user;
    protected string $_password;
    protected mysqli $_connection;
    protected string $_database;

    /**
     * @param string $user User's name
     * @param string $password User's password
     * @param string $database Database name
     * @param string $host Host of database (default 127.0.0.1)
     * @param string $port Port of database (default 3306)
     */
    public function __construct(string $user, string $password, string $database = "",
                                string $host = "127.0.0.1", string $port = "3306")
    {
        $this->_user = $user;
        $this->_password = $password;
        $this->_host = $host;
        $this->_port = $port;
        $this->_database = $database;
    }
    /**
     * @return void
     */
    public function Open(): void
    {
        $this->_connection = new mysqli(
            $this->_host,
            $this->_user,
            $this->_password,
            $this->_database,
            $this->_port,
        );
        if ($this->_connection->connect_errno)
        {
            throw new RuntimeException('Connect exception', $this->_connection->error);
        }
    }
    /**
     * @return void
     */
    public function Close(): void
    {
        $this->_connection->close();
    }

    /**
     * @return mysqli
     */
    public function getConnection(): mysqli
    {
        return $this->_connection;
    }
}