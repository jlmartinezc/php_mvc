<?php 

namespace src\Router;

class Router{

    private $routePath = null;
    protected Array $routes = [];
    protected Array $params = [];
    protected Array|null $route = null;


    public function __construct(){
        $routes = sprintf('%s/routes/routes.php', dirname(__FILE__, 3));

        (!is_file($routes))? throw new \Exception("No hay rutas definidas") : '';

        $this->routes = include($routes);
    }

    /**
     * @return Void
     */
    public function dispatch():Void{
        try {
            $url = $_SERVER['PHP_SELF'];
            $method = $_SERVER['REQUEST_METHOD'];
            
            if($url === '') $url = '/';

            $this->route = $this->checkRoute($url, $method);

            if($this->route === null) throw new \Exception("No hay ruta coincidente", 400);
            
            $this->params = $this->extractUrlParams($url);

            $this->runRoute();

        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }

    /**
     * @param String $url
     * @param String $method
     * 
     * @return Array|null
     */
    private function checkRoute(String $url, String $method):?Array{
        try {
            foreach ($this->routes[$method] as $path => $routes) {
                if(strpos($url, $path) === 0){
                    $this->routePath = $path;
                    return $this->decodeController($routes);
                }
            }

            return null;
        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }

    /**
     * @param String $routes
     * 
     * @return Array
     */
    private function decodeController(String $route):Array{
        $route = explode('@', $route);

        return [
            'controller' => sprintf('App\\Controllers\\%sController', $route[0]),
            'action' => $route[1]
        ];
    }

    /**
     * @param String $url
     * 
     * @return Array
     */
    private function extractUrlParams(String $url):Array{
        try {
            $params_string = str_replace($this->routePath, '', $url);

            if(substr($params_string, 0, 1) === '/') $params_string = substr($params_string, 1);

            $params = [];
            $arr = explode('/', $params_string);

            $arr = explode("/", $params_string);

            for ($index = 0; $index < count($arr); $index++) {
                $params[$arr[$index]] = (isset($arr[$index + 1])) ? $arr[$index + 1] : null;

                $index++;
            }
            return $params;

        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }

    /**
     * @return Void
     */
    private function runRoute():Void{
        try {
            if(class_exists($this->route['controller'])){
                $controllerObject = new $this->route['controller']();

                $this->methodExist($controllerObject);
            } else{
                throw new \Exception(sprintf("El controlador '%s' no ha sido encontrado", $this->route['controller']));
            }

        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }


    /**
     * @param Object $controller
     * 
     * @return Void
     */
    private function methodExist(Object $controller):Void{
        try {
            if(method_exists($controller, $this->route['action'])){
                (is_callable([$controller, $this->route['action']]))
                ? call_user_func_array([$controller, $this->route['action']], [])
                : throw new \Exception(sprintf("Metodo '%s' en el controlador '%s' no es ejecutable", $this->route['action'], $this->route['controller']));

            } else{
                throw new \Exception(sprintf("El metodo '%s' no ha sido definido", $this->route['action']));
            }

        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }
}