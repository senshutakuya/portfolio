<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/common.php');
require_once($root . 'functions/user.php');

if (empty($_SESSION['join'])) {
    $_SESSION['join']['name'] = '';
    $_SESSION['join']['email'] = '';
    $_SESSION['join']['password'] = '';
}

$keywords = [
    'join_css' => $root_url . 'statics/css/join.css',
    'post_name' => $_SESSION['join']['name'],
    'post_email' => $_SESSION['join']['email'],
    'post_password' => $_SESSION['join']['password'],
    'err_name' => '',
    'err_email' => '',
    'err_password' => '',
    'err_password_length' => '',
    'err_image' => '',
    'err_image_type' => '',
    'err_alert' => 'hidden',
    'login_url' => $root_url . 'login'
];
$error = false;

if (!empty($_POST)) {

    if($_POST['name'] == ''){
        $keywords['err_name'] = 'ユーザー名を入力してください';
        $error = true;
    }else{
        $keywords['post_name'] = $_POST['name'];
    }

    if ($_POST['email'] == '') {
        $keywords['err_email'] = 'メールアドレスを入力してください';
        $error = true;
    }else{
        $keywords['post_email'] = $_POST['email'];
        if(getUserFromEmail($_POST['email']) != null){
            $keywords['err_email'] = '指定されたメールアドレスはすでに登録されています';
            $error = true;
        }
    }

    if($_POST['password'] == ''){
        $keywords['err_password'] = 'パスワードを入力してください';
        $error = true;
    }else{
        $keywords['post_password'] = $_POST['password'];
        if(strlen($_POST['password']) < 4){
            $keywords['err_password_length'] = 'パスワードは４文字以上で入力してください';
            $error = true;
        }
    }

    if($_FILES['image']['error'] != 4){
        if(($_FILES['image']['error'] != 0) or ($_FILES['image']['type'] != 'image/jpeg' and $_FILES['image']['type'] != 'image/png')){
            $keywords['err_image_type'] = '「jpg/jpeg」または「png」形式の画像をアップロードしてください';
            $error = true;
        }
    }else{
        $not_upload = true;
    }

    if(!$error){
        $_SESSION['join'] = $_POST;
        if(empty($not_upload)){
            $fp = fopen($_FILES['image']['tmp_name'], 'rb');
            $img = fread($fp, filesize($_FILES['image']['tmp_name']));
            fclose($fp);
            $_SESSION['join']['image'] = [
                'type' => $_FILES['image']['type'],
                'tmp_content' => 'data:' . $_FILES['image']['type'] . ';base64,' . base64_encode($img),
                'content' => file_get_contents($_FILES['image']['tmp_name']),
                'size' => $_FILES['image']['size']
            ];
        }else{
            $_SESSION['join']['image'] = null;
        }
        header('Location: ' . $root_url . 'join/check.php');
        exit;
    }else{
        $keywords['err_alert'] = '';
        $keywords['err_image'] = '* 恐れ入りますが、画像を改めて指定してください';
    }
}

$html = create_page(
    $root . 'templates/join/join.html',
    'ユーザー登録',
    [],
    $keywords,
    false
);

print($html);
