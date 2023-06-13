<?php

/**
 * 
 * 認証に関するファイル。
 * 
 * 作成者：西島
 * 
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'classes/database.php');
require_once($root . 'functions/user.php');

function auto_login()
{

    global $root;
    $db = new Database();
    $db->setSQL('SELECT * FROM `sessions` WHERE `token` = ?;');
    $db->setBindArray([$_COOKIE['PHPSESSID']]);
    $db->execute();

    if ($pass = $db->fetch()){
        $_SESSION['id'] = $pass['user_id'];
        $_SESSION['time'] = time();
        header('Location: ../account/user_page.php');
        exit;
    }else{
        header('Location: /account/logout.php');
        exit;
    }
}

function login(string $email, string $password, bool $auto_login = false) : bool
{

    $user = getUserFromEmail($email);

    if($user == null){
        return false;
    }

    $db = new Database();
    $db->setSQL('SELECT `password` FROM `users` WHERE `id` = ?;');
    $db->setBindArray([$user->getId()]);
    $db->execute();
    $res = $db->fetch();

    if(password_verify($password, $res['password']) == false){
        return false;
    }

    $_SESSION['user_id'] = $user->getId();
    return true;

}

function logout() : void
{
    $_SESSION = [];
    setcookie('PHPSESSID', '', time() - 99999, '/');
    session_destroy();
}

function setLoginToken($user_id) : void
{

    if (isset($_COOKIE['token'])) {
        $db = new Database();
        $db->setSQL('DELETE FROM `sessions` WHERE token=?;');
        $db->setBindArray([$_COOKIE['token']]);
        $db->execute();
    }

    $db = new Database();
    $db->setSQL('INSERT INTO `sessions` (`token`, `user_id`) VALUES(?, ?);');
    $db->setBindArray([
        $_COOKIE['PHPSESSID'],
        $user_id
    ]);
    $db->execute();

    setcookie('token',$_COOKIE["PHPSESSID"], time() + 60 * 60 * 24 * 7, '/');

}
