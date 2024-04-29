<?php
session_start();


error_reporting(E_ALL ^ E_WARNING); // disable warnings
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PATCH,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");

require_once "support/constants.php";
require_once "support/requestDecliner.php";
require_once "support/printer.php";
require_once "support/databaseHelper.php";
require_once "support/functions.php";

require_once "businessLogic/Post.php";
require_once "businessLogic/Topic.php";
require_once "businessLogic/TopicWithoutChildren.php";
require_once "businessLogic/UserWithPosts.php";

// Определяем метод запроса
$method = $_SERVER['REQUEST_METHOD'];
// Получаем данные из тела запроса
$formData = getFormData($method);

// Разбираем url
$url = (isset($_GET['q'])) ? $_GET['q'] : '';
$url = rtrim($url, '/');
$urls = explode('/', $url);

// Определяем роутер и url data
$router = $urls[0];
$urlData = array_slice($urls, 1);

ConfigureDB();

if (isset($url) && $router != ""){
    // Подключаем файл-роутер и запускаем главную функцию
    $file = 'routers/' . $router . '.php';
    if (!file_exists($file)) {
        header('HTTP/1.0 404 Bad Request');
        echo json_encode(array(
            'error' => 'Bad Request'
        ));
    }
    else {
    include_once $file;
    route($method, $urlData, $formData);
    }
}
else {
    header('HTTP/1.0 400 Bad Request');
    echo json_encode(array(
        'error' => 'Bad Request'
    ));
}

function getFormData($method) {
    if ($method === 'GET') return $_GET;
    if ($method === 'POST' && !empty($_POST)) return $_POST;

    $incomingData = file_get_contents('php://input');
    $decodedJSON = json_decode($incomingData); //пытаемся преобразовать то, что нам пришло из JSON в объект PHP
    if ($decodedJSON)
    {
        $data = $decodedJSON;
    }
    else
    {
        $data = array();
        $exploded = explode('&', file_get_contents('php://input'));
        foreach($exploded as $pair)
        {
            $item = explode('=', $pair);
            if (count($item) == 2)
            {
                $data[urldecode($item[0])] = urldecode($item[1]);
            }
        }
    }
    return $data;
}


?>
