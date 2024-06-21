<?php

class DBConnection
{
    protected string $_host;
    protected string $_port;
    protected string $_user;
    protected string $_password;
    protected PDO $_connection;
    protected string $_database;
    protected string $_driver;

    /**
     * @param string $user User's name
     * @param string $password User's password
     * @param string $database Database name
     * @param string $host Host of database (default 127.0.0.1)
     * @param string $port Port of database (default 3306)
     */
    public function __construct(string $user, string $password, string $database = "", $driver = "mysql",
                                string $host = "127.0.0.1", string $port = "3306")
    {
        $this->_user = $user;
        $this->_password = $password;
        $this->_host = $host;
        $this->_port = $port;
        $this->_database = $database;
        $this->_driver = $driver;
    }
    /**
     * @return void
     */
    final protected function Open(): void
    {
        $connectionString = $this->_driver . ":" . "host=" . $this->_host  . ";port=" .
            $this->_port . ";dbname=" .  $this->_database;
        try
        {
            $this->_connection = new PDO($connectionString, $this->_user, $this->_password);
        }
        catch(PDOException $exp)
        {
            throw new RuntimeException('Connect exception', $exp->getCode()  .  ":  ".  $exp->getMessage());
        }
    }

    /**
     * @param string $query SQL query
     * @param array|null $params params to be found in query
     * @param bool $transactional Does the transaction needed for several queries
     * @return PDOStatement
     */
    public function ExecuteQuery(string $query, array $params = null, bool $transactional = false): PDOStatement
    {
        $pdoStatement = null;
        try {
            $this->Open();
            $pdoStatement =  $this->_connection->prepare($query);
            if (isset($params))
                foreach ($params as $param => $value) {
                    $pdoStatement->bindParam(":" .  $param, $value);
                }
            if (!$transactional)
                $this->_connection->beginTransaction();
            $result = $pdoStatement->execute();
            if (!$transactional)
                $this->_connection->commit();
        }
        catch (PDOException $exception) {
            if (!$transactional)
                $this->_connection->rollBack();
            throw new RuntimeException("WRONG SQL", 180001);
        }
        return $pdoStatement;
    }
}