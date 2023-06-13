<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/common.php');
require_once($root . 'functions/post.php');
require_once($root . 'functions/timeline.php');
require_once($root . 'config/login_required.php');

$posts = '';

if(isset($_POST['post-content'])){
    sendPost(
        getUserFromId($_SESSION['user_id']),
        null,
        $_POST['post-content']
    );
    header('Location: ./');
    exit;
}

foreach(searchPosts(reply: false, amount: 100) as $post){
    $posts = $posts . createPost($post->getId(), $_SESSION['user_id']);
}

$icon_id = getUserFromId($_SESSION['user_id'])->getPictureId();

if($icon_id != null){
    $icon_path = '/home/icon.php?id=' . $icon_id;
}else{
    $icon_path = 'https://pics.prcm.jp/654b637d854c5/84936407/png/84936407.png';
}

$html = create_page(
    file_path: $root . 'templates/home/timeline.html',
    sub_title: 'ホーム',
    heads: [
        '<link rel="stylesheet" href="' . $root_url . 'statics/css/home.css">'
    ],
    keywords: [
        'posts' => $posts
    ],
    icon_path: $icon_path
);

print($html);
