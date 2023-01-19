<?php 
declare(strict_types = 1);

namespace src\Autoloader;

class Autoloader {
    protected Array $config;

    public function __construct(){
        $this->config = include_once '../config/app.php';
    }

    /**
     * @return Void
     */
    public function register():Void{
        spl_autoload_register([$this, 'loader']);
    }

    /**
     * @param String $class
     * 
     * @return Void
     */
    public function loader(String $class):Void{
        try {
            $class = trim($class, '/');
            $root = $this->config['path']['root'];
            $file = sprintf('%s/%s.php', $root, $class);

            (is_file($file))
            ? require_once $file
            : throw new \Exception(sprintf("No se ha encontrado la clase '%s'", $class), 404);

        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }
}