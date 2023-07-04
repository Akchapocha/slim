<?php

use PriNikApp\FrontTest\Action\ErrorAction;

function debug(mixed $arr, int $stop = 0): void
{
    echo '<pre>';
    print_r($arr);
    $stop !== 1 ? : exit('</pre>');
    echo '</pre>';
}

function getAction(): string
{
    $uri = substr($_SERVER['REQUEST_URI'], 1);
    $fullNameAction = getFullNameAction(getClassFromRequest($uri));
    if (class_exists($fullNameAction)) {
        return $fullNameAction;
    } else {
        $fullNameAction = getFullNameAction(getClassFromDictionary($uri));
        return class_exists($fullNameAction) ? $fullNameAction : getFullNameAction('error');
    }
}

function getClassFromRequest($uri): string
{
    $words = $uri === '' ? ['main', 'page'] : explode('_', $uri);
    $class = '';
    foreach ($words as $word) {
        $class .= ucfirst($word);
    }
    return $class;
}

function getFullNameAction($action): string
{
    $nameSpace = new ReflectionClass(ErrorAction::class);
    return $nameSpace->getNamespaceName() . '\\' . $action . 'Action';
}

/**
 * Словарь для правильного именования классов.
 * Временная мера.
 * В дальнейшем необходимо в таблице `stage_access` - переименовать названия модулей.
 *
 * @param string $uri
 * @return string
 */
function getClassFromDictionary(string $uri): string
{
    $actions = [
        'access_list' => 'AccessList',
        'add_users' => 'AddUser',
        'check_mark' => 'CheckMark',
        'chk_serial' => 'CheckSerial',
        'delete_product_from_completed_invoice' => 'DeleteProduct',
        'edit_product_properties' => 'EditProduct',
        'find_new_products' => 'FindNewProduct',
        'forbidden_serials' => 'ForbiddenSerial',
        'get_serials' => 'GetSerial',
        'goods' => 'Good',
        'history_product' => 'HistoryProduct',
        'input_main' => 'InputMain',
        'marketing' => 'Marketing',
        'output' => 'Output',
        'show_product_properties' => 'ShowProduct',
        'skel_test' => 'SkelTest',
        'statistic' => 'Statistic',
        'torg_2_list' => 'TorgList',
        'error' => 'Error'
    ];
    return $actions[$uri];
}
