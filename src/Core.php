<?php

namespace src;

require_once 'Autoloader/Autoloader.php';
require_once 'Router/Router.php';

use src\Autoloader\Autoloader;
use src\Router\Router;

class Core{
    
    public function __construct(){
    }

    /**
     * @return Void
     */
    static public function run():Void{
        session_start();
        static::loader();
        static::dispatcher();
    }

    /**
     * @return Void
     */
    static private function loader():Void{
        $loader = new Autoloader();
        $loader->register();
    }

    /**
     * @return Void
     */
    static private function dispatcher():Void{
        $router = new Router();
        $router->dispatch();
    }
}
