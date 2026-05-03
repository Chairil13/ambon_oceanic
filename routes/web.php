<?php

class Router {
    
    public function route($url) {
        $url = rtrim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);

        $controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
        $method = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';
        $params = array_slice($url, 2);

        $controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerName();
            
            if (method_exists($controller, $method)) {
                call_user_func_array([$controller, $method], $params);
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }

    private function notFound() {
        http_response_code(404);
        echo "404 - Page Not Found";
    }
}
