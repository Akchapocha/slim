<?php

use PriNikApp\FrontTest\Action\ErrorAction;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . '/functions.php';
$container = require_once __DIR__ . '/bootstrap.php';
AppFactory::setContainer($container);
$app = AppFactory::create();

session_start();
$_SESSION['uid'] = '7128';
//$_SESSION['uid'] = '6885';

$app->group($_SERVER['REQUEST_URI'], callable: function (RouteCollectorProxy $group) {
    $action = getAction();
    $group->get('', $action);
    if (isset($_POST['action'])) {
        $group->post(
            '',
            method_exists($action, $_POST['action']) ? [$action, $_POST['action']] : ErrorAction::class
        );
    }
});

$app->run();
