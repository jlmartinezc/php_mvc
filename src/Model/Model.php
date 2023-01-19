<?php 
declare(strict_types = 1);

namespace src\Model;

class Model{
    Protected static $db = null;
    protected Array $config = [];

    public function __construct(){
        $this->config = include_once '../config/db.php';
        $this->connection();
    }

    /**
     * @return Void
     */
    private function connection():Void{
        try {
            $host = $this->config['host'];
            $user = $this->config['user'];
            $pass = $this->config['pass'];
            $name = $this->config['name'];
            $charset = $this->config['charset'];

            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];

            $dsn = sprintf('mysql:localhost=%s;dbname=%s;charset=%s', $host, $name, $charset);

            static::$db = new \PDO($dsn, $user, $pass, $options);

        } catch (\PDOException $error) {
            echo $error->getMessage();
            die(json_encode(['result' => false, 'message' => 'Error en la conexion']));
        }

    }

    private function DB(){
        return static::$db;
    }
}