<?php 
namespace src\View;

class View {

    /**
     * @param String $view
     * @param Array $params
     * 
     * @return Void
     */
    static public function render(String $view, Array $params = []):Void{
        try {
            $config = sprintf('%s/config/app.php', dirname(__FILE__, 3));
            $config = include($config);

            (empty($config) || is_null($config)) 
            ? throw new \Exception("Datos de configuración no han sido encontrados", 404) : '';

            extract($params, EXTR_SKIP);
            $root = $config['path']['root'];
            $content = sprintf('%s/App/Views/pages/%s.php', $root, $view);
            $template = sprintf('%s/App/Views/template.php', $root);

            (is_readable($content))
            ? require_once($template)
            : throw new \Exception(sprintf("La vista '%s' no ha sido encontrada", $view), 404);

        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }

    /**
     * @param Array $params
     * 
     * @return Void
     */
    static public function renderError(Array $params = []):Void{
        try {
            $config = sprintf('%s/config/app.php', dirname(__FILE__, 3));
            $config = include($config);

            (empty($config) || is_null($config)) 
            ? throw new \Exception("Datos de configuración no han sido encontrados", 404) : '';

            extract($params, EXTR_SKIP);
            $root = $config['path']['root'];
            $error = sprintf('%s/App/Views/error.php', $root);

            (is_readable($error))
            ? require_once($error)
            : throw new \Exception(sprintf("La vista '%s' no ha sido encontrada", $view), 404);

        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }
}