<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/public', '', $path);

        if ($path === '') $path = '/';

        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([a-zA-Z0-9]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                return $this->executeCallback($callback, $matches);
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }

    private function executeCallback($callback, $params = []) {
        // Jika callback dalam format string "Controller@method"
        if (is_string($callback) && str_contains($callback, '@')) {
            [$controllerName, $method] = explode('@', $callback);

            // Tentukan path file controller
            $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

            // Pastikan file controller ada
            if (!file_exists($controllerFile)) {
                throw new Exception("Controller file not found: $controllerFile");
            }

            require_once $controllerFile;

            // Pastikan class ada
            if (!class_exists($controllerName)) {
                throw new Exception("Controller class not found: $controllerName");
            }

            $controllerInstance = new $controllerName();

            // Pastikan method ada
            if (!method_exists($controllerInstance, $method)) {
                throw new Exception("Method $method not found in controller $controllerName");
            }

            // Jalankan controller dan method dengan parameter
            return call_user_func_array([$controllerInstance, $method], $params);
        }

        // Jika callback berupa fungsi anonim atau callable lain
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        throw new Exception("Invalid callback type for route");
    }
}