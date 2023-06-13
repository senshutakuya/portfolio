<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/common.php');

/*
if(empty($_SESSION['join'])){
    header('Location: ' . $root_url . '../home');
    exit;
}
*/
unset($_SESSION['join']);
$html = create_page(
    $root . 'templates/join/complete.html',
    '登録完了',
    [],
    [
        'complete_css' => $root_url . 'statics/css/complete.css',
        'home_url' => $root_url . 'home'
    ],
    false
);

print($html);