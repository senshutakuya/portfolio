<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/user.php');
require_once($root . 'functions/common.php');
require_once($root . 'config/login_required.php');

if(empty($_SESSION['pwd_chg'])){
    header('Location: /home/');
    exit;
}

unset($_SESSION['pwd_chg']);
$user = getUserFromId($_SESSION['user_id']);

$html = create_page(
    $root . 'templates/setting/change_password_complete.html',
    '変更完了',
    [],
    [
        'icon_path' => $user->getPictureUrl()
    ],
    icon_path: $user->getPictureUrl()
);

print($html);
