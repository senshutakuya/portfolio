<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/user.php');
require_once($root . 'functions/common.php');
require_once($root . 'config/login_required.php');

$user = getUserFromId($_SESSION['user_id']);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_FILES['icon']) and $_FILES['icon']['error'] == 0){
        changeImage($user, $_FILES['icon']['type'], file_get_contents($_FILES['icon']['tmp_name']), $_FILES['icon']['size'],);
    }
    if(isset($_POST['name'])){
        $user->setName($_POST['name']);
    }
    if(isset($_POST['email'])){
        $user->setEmail($_POST['email']);
    }
    $user->save();
    header('Location: /user/');
    exit;
}

$html = create_page(
    $root . 'templates/setting/setting.html',
    '設定',
    [],
    [
        'icon_path' => $user->getPictureUrl(),
        'username' => $user->getName(),
        'email' => $user->getEmail()
    ],
    icon_path: $user->getPictureUrl()
);

print($html);
