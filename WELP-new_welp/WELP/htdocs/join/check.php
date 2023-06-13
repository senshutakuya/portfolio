<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'classes/database.php');
require_once($root . 'functions/common.php');
require_once($root . 'functions/user.php');

if(empty($_SESSION['join'])){
    header('Location: ' . $root_url . 'join/index.php');
    exit;
}

if(isset($_GET['confirm'])){
    if($_SESSION['join']['image'] != null){
        $db = new Database();
        $db->setSQL('INSERT INTO `images` (`image_type`, `image_content`, `image_size`) VALUES (:image_type, :image_content, :image_size);');
        $db->setBindArray([
            ':image_type' => $_SESSION['join']['image']['type'],
            ':image_content' => $_SESSION['join']['image']['content'],
            ':image_size' => $_SESSION['join']['image']['size']
    ]);
    $db->execute();
    $image_id = $db->getLastInsertId();
    }else{
        $image_id = null;
    }
    createUser(
        $_SESSION['join']['name'],
        $_SESSION['join']['email'],
        password_hash($_SESSION['join']['password'], PASSWORD_BCRYPT),
        $image_id
    );
    unset($_SESSION['join']);
    header('Location: ' . $root_url . 'join/complete.php');
    exit;
}

if(isset($_SESSION['join']['image'])){
    $img = $_SESSION['join']['image']['tmp_content'];
}else{
    $img = 'https://pics.prcm.jp/654b637d854c5/84936407/png/84936407.png';
}

$html = create_page(
    $root . 'templates/join/check.html',
    '確認画面',
    [],
    [
        'check_css' => $root_url . 'statics/css/check.css',
        'name' => htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES),
        'email' => htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES),
        'img' => $img
    ],
    false
);

print($html);
