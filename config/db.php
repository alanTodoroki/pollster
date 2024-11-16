<?php
class Database
{
    private $servername = "localhost";
    private $database = "pollsters";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);

            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }

            $this->conn->set_charset("utf8");
        } catch (Exception $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
