<?php 

namespace src\Model;

use PDO;

class Models{
    private static Object $db = null;
    private Array $config = [];

    public function __construct(){
        $config = sprintf('%s/config/db.php', dirname(__FILE__, 3));
        $this->config = include($config);

        $this->connection();
    }

    /**
     * @return Void
     */
    private function connection():Void{
        try {
            (empty($this->config) || is_null($this->config)) 
            ? throw new \Exception("Datos de configuración no han sido encontrados", 404) : '';

            if(static::$db === null){
                $host = $this->config['host'];
                $user = $this->config['user'];
                $pass = $this->config['pass'];
                $name = $this->config['name'];
                $charset = $this->config['charset'];

                $options = [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ];

                $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $host, $name, $charset);

                static::$db = new \PDO($dsn, $user, $pass, $options);
            }
        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }

    /**
     * @return Object|null
     */
    public function DB():?Object{
        return static::$db;
    }
}