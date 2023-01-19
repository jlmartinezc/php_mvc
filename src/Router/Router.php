<?php 
declare(strict_types = 1);

namespace src\Router;

class Router{

    protected Array $routes = [];
    protected Array $params = [];
    protected Array $matches = [];
    protected Array|Null $route = null;

    public function __construct(){
        $this->routes = include_once '../routes/routes.php';
    }

    /**
     * @return Void
     */
    public function dispatch():Void{
        $url = $_GET['url'];
        $method = $_SERVER['REQUEST_METHOD'];

        $this->route = $this->checkRoute($url, $method);
        
        ($this->route === null) ? throw new \Exception("No se ha encontrado la ruta", 404) : '';

        $this->run();
        
    }
    
    /**
     * @param String $url
     * @param String $method
     * 
     * @return Array|null
     */
    private function checkRoute(String $url, String $method):?Array{
        try {
            foreach($this->routes[$method] as $path => $route){
                
                $controller = ($this->match($url, $path))
                ? $this->decodeControllers($route)
                : null;
                
                return $controller;
            }

            return null;

        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }

    /**
     * @param String $route
     * 
     * @return Array
     */
    private function decodeControllers(String $route):Array{
        $route = explode('@', $route);

        return [
            'controller' => sprintf('App\\Controllers\\%sController', $route[0]),
            'method' => $route[1]
        ];
    }

    /**
     * @param String $url
     * @param String $path
     * 
     * @return Bool
     */
    private function match(String $url, String $path):Bool{
        try {
            $url = trim($url, '/');
            $path = trim($path, '/');
            $routePath = preg_replace_callback('#:([\w]+)#', [$this, 'paramsMatch'], $path);
            $regex = "#^$routePath$#i";

            if(!preg_match($regex, $url, $matches)) return false;

            array_shift($matches);
            $this->params = $matches;
            return true;

        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }

    /**
     * @param Array $match
     * 
     * @return String
     */
    private function paramsMatch(Array $match):String{
        return (isset($this->matches[$match[1]]))
        ? sprintf('(%s)', $this->matches[$match[1]])
        : '([^/]+)';
    }

    /**
     * @return Void
     */
    private function run():Void{
        try {
            if(class_exists($this->route['controller'])){
                $controllerObject = new $this->route['controller']();
                $this->methodExist($controllerObject);
            } else{
                throw new \Exception(sprintf("El controlador '%s' no ha sido encontrado", $this->route['controller']), 404);                
            }

        } catch (\exception $error) {
            echo $error->getMessage();
            die();
        }
    }

    /**
     * @param Object $controllerObject
     * 
     * @return Void
     */
    private function methodExist(Object $controllerObject):Void{
        try {
            if(method_exists($controllerObject, $this->route['method'])){
                
                (is_callable([$controllerObject, $this->route['method']]))
                ? call_user_func_array([$controllerObject, $this->route['method']], $this->params)
                : throw new \Exception(sprintf("Metodo '%s' en el controlador '%s' no es ejecutable", $this->route['action'], $this->route['controller']));
            }else{
                throw new \Exception(sprintf("El metodo '%s' no ha sido definido", $this->route['method']));
            }

        } catch (\exception $error) {
            echo $error->getMessage();
            die();
        }
    }
}