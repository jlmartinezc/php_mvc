<?php 
declare(strict_types = 1);

namespace src\View;

class View{

    public function __construct(){
    }

    /**
     * @param String $view
     * @param Array $params
     * 
     * @return Void
     */
    public static function render(String $view, Array $params = []):Void{
        try {
            $config = include_once '../config/app.php';
            $root = $config['path']['root'];
            $template = sprintf('%s/App/Views/template.php', $root);
            $content = sprintf('%s/App/Views/pages/%s.php', $root, $view);

            extract($params, EXTR_SKIP);

            (is_readable($content))
            ? require_once $template
            : throw new \Exception(sprintf("La vista '%s' no ha sido encontrada"), 404);
                
        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }
}