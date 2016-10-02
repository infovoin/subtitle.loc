<?php

use App\Classes\Logs;
use App\Classes\View;
use App\Exceptions\EDb;
use App\Exceptions\E404Ecxeption;

require __DIR__ . '/autoload.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', $path);

$ctrl = (!empty($pathParts[1]) && $pathParts[1] != 'index.php') ? ucfirst($pathParts[1]) : 'Video';
$act = (!empty($pathParts[2])) ? ucfirst($pathParts[2]) : 'All';

$controllerClassName = 'App\\Controllers\\' . $ctrl;

$controller = new $controllerClassName;
$method = 'action' . $act;


try {
    $controller->$method();

} catch (E404Ecxeption $e) {

    $log = new Logs();
    $log->FillAndWrite($e);



} catch (EDb $e) {
    $log = new Logs();
    $log->FillAndWrite($e);

} catch (Exception $e) {
    $view = new View();
    $view->error = $e->getMessage();
    $view->display('error/404.php');

    $log = new Logs();
    $log->FillAndWrite($e);

}