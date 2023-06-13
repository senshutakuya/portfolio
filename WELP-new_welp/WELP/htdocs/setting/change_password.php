<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'classes/database.php');
require_once($root . 'functions/user.php');
require_once($root . 'functions/common.php');
require_once($root . 'config/login_required.php');

$user = getUserFromId($_SESSION['user_id']);
$errors = [
    'old_pwd_incollect' => '',
    'pwd_length' => '',
    'new_pwd_incollect' => ''
];

if(isset($_POST['old_pwd']) and isset($_POST['new_pwd']) and isset($_POST['new_pwd_cfm'])){
    $db = new Database();
    $db->setSQL('SELECT `password` FROM `users` WHERE `id` = ?;');
    $db->setBindArray([$user->getId()]);
    $db->execute();
    $enc_pwd = $db->fetch()['password'];
    if(!password_verify($_POST['old_pwd'], $enc_pwd)){
        $errors['old_pwd_incollect'] = 'パスワードが間違っています';
        $error = true;
    }
    if(strlen($_POST['new_pwd']) < 4){
        $errors['pwd_length'] = 'パスワードは4桁以上に設定して下さい';
        $error = true;
    }
    if($_POST['new_pwd'] !== $_POST['new_pwd_cfm']){
        $errors['new_pwd_incollect'] = 'パスワードが間違っています';
        $error = true;
    }
    if(empty($error)){
        $enc_pwd = password_hash($_POST['new_pwd'], PASSWORD_BCRYPT);
        $db = new Database();
        $db->setSQL('UPDATE `users` SET `password`=?, `updated_at`=NOW() WHERE `id`=?;');
        $db->setBindArray([$enc_pwd, $user->getId()]);
        $db->execute();
        $_SESSION['pwd_chg'] = true;
        header('Location: /setting/change_password_complete.php');
        exit;
    }
}

$html = create_page(
    $root . 'templates/setting/change_password.html',
    'パスワード変更',
    [],
    array_merge(
        [
            'icon_path' => $user->getPictureUrl()
        ],
        $errors
    ),
    icon_path: $user->getPictureUrl()
);

print($html);
