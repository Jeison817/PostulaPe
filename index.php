<?php
session_start();

// 1. Cargar la clase de conexión
require_once 'config/database.php';
$db   = new Database();
$conn = $db->getConnection();

// 2. Obtener controller y action desde la URL
$controller = $_GET['controller'] ?? 'login';
$action     = $_GET['action'] ?? 'index';

// 3. Armar ruta del archivo del controlador
$controllerFile = "controllers/{$controller}Controller.php";

// 4. Verificar si existe
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controllerName = $controller . "Controller";

    // 5. Verificar el constructor del controlador
    $reflectionClass = new ReflectionClass($controllerName);
    $constructor     = $reflectionClass->getConstructor();

    if ($constructor && $constructor->getNumberOfParameters() > 0) {
        // Si el constructor espera parámetros → pasamos la conexión
        $controllerObj = new $controllerName($conn);
    } else {
        // Si no tiene parámetros → lo instanciamos vacío
        $controllerObj = new $controllerName();
    }

    // 6. Verificar si el método existe
    if (method_exists($controllerObj, $action)) {
        // 7. Obtener los parámetros del método y mapearlos desde $_GET
        $reflection = new ReflectionMethod($controllerObj, $action);
        $params     = $reflection->getParameters();
        $args       = [];

        foreach ($params as $param) {
            $paramName     = $param->getName();
            $args[$paramName] = $_GET[$paramName] ?? null;
        }

        // 8. Llamar al método con los argumentos
        call_user_func_array([$controllerObj, $action], $args);
    } else {
        die("Acción '$action' no encontrada en controlador '$controllerName'.");
    }
} else {
    die("Controlador '$controller' no encontrado.");
}
