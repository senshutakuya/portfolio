<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/common.php');
require_once($root . 'functions/auth.php');
require_once($root . 'functions/user.php');

if(isset($_SESSION['user_id'])){
    if(getUserFromId($_SESSION['user_id']) != null){
        header('Location: ' . $root_url . 'home');
        exit;
    }
}

$keywords = [
    'post_email' => '',
    'post_password' => '',
    'password_valid'=> '',
    'err_login_failed' => '',
    'login_css' => $root_url . 'statics/css/login.css',
    'alert' => 'hidden',
    'join_url' => $root_url . 'join'
];

if( isset($_POST['email']) && isset($_POST['password']) ){

    $keywords['post_email'] = $_POST['email'];
    $keywords['post_password'] = $_POST['password'];

    if(isset($_POST['save'])){
        $res = login($_POST['email'], $_POST['password'], true);
    }else{
        $res = login($_POST['email'], $_POST['password'], false);
    }

    if($res){
        header('Location: ' . $root_url . 'home');
        exit;
    }else{
        $keywords['alert'] = '';
    }
}

$html = create_page(
    $root . 'templates/login/login.html',
    'ログイン',
    [],
    $keywords,
    false
);

print($html);
