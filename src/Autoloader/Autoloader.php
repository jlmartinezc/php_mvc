<?php 

namespace src\Autoloader;

class Autoloader{

    private Array $config = [];

    public function __construct(){
        $config = sprintf('%s/config/app.php', dirname(__FILE__, 3));
        $this->config = include($config);
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
            (empty($this->config) || is_null($this->config)) 
            ? throw new \Exception("Datos de configuración no han sido encontrados", 404) : '';
            
            $class = trim($class, '/');
            $root = $this->config['path']['root'];
            $file = sprintf('%s/%s.php', $root, $class);

            (is_file($file))
            ? require_once($file)
            : throw new \Exception(sprintf("No se ha encontradp eñ archivo '%s.php'", $class), 404);
                        
        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }
}