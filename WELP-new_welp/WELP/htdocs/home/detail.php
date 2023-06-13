<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/common.php');
require_once($root . 'functions/post.php');
require_once($root . 'functions/timeline.php');
require_once($root . 'config/login_required.php');

if(empty($_GET['post_id'])){
    header('Location: ' . $root_url . '/home');
    exit;
}

$post = getPost($_GET['post_id']);

if(isset($_POST['content'])){
    sendPost(getUserFromId($_SESSION['user_id']), $post, $_POST['content']);
    header('Location: ./detail.php?post_id=' . $_GET['post_id']);
    exit;
}

$author = $post->getUser();
$replies = $post->getReplies();
$repliesAsHTML = '';

foreach($replies as $reply){
    $repliesAsHTML = $repliesAsHTML . createPost($reply->getId(), $_SESSION['user_id']);
}

if(getUserFromId($_SESSION['user_id'])->isFavorite($post->getId())){
    $fill = 'red';
}else{
    $fill = 'none';
}

$html = create_page(
    $root . 'templates/home/detail.html',
    '詳細',
    [],
    [
        'icon_path' => $author->getPictureUrl(),
        'name' => $author->getName(),
        'created_at' => getFormattedCreatedAt($post->getId()),
        'content' => $post->getContent(),
        'replies' => $repliesAsHTML,
        'post_origin_id' => $post->getId(),
        'favorite_fill' => $fill
    ],
    icon_path: getUserFromId($_SESSION['user_id'])->getPictureUrl()
);

print($html);
