<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/common.php');
require_once($root . 'functions/user.php');
require_once($root . 'functions/post.php');
require_once($root . 'functions/timeline.php');
require_once($root . 'config/login_required.php');

if(empty($_GET['user_id'])){
    header('Location: /user/?user_id=' . $_SESSION['user_id']);
    exit;
}else{
    $target_id = $_GET['user_id'];
}

$target = getUserFromId($target_id);

if($target == null){
    header('Location: /home');
    exit;
}

$client = getUserFromId($_SESSION['user_id']);

if($_GET['user_id'] == $_SESSION['user_id']){
    $block = file_get_contents($root . 'templates/user/my_block.html');
}else{
    $block = file_get_contents($root . 'templates/user/other_block.html');
}

if($client->isFollow($target)){
    $follow_class = 'followed';
    $follow_text = 'フォロー中';
}else{
    $follow_class = '';
    $follow_text = 'フォロー';
}

$posts = '';

foreach(searchPosts(reply: true, user: $target, amount:100) as $post){
    $posts = $posts . createPost($post->getId(), $_SESSION['user_id']);
}

$html = create_page(
    $root . 'templates/user/user_page.html',
    'マイページ',
    [],
    [
        'name' => $target->getName(),
        'icon_path' => $target->getPictureUrl(),
        'following' => $target->getFollowing(),
        'follower' => $target->getFollower(),
        'block' => $block,
        'posts' => $posts,
        'follow_class' => $follow_class,
        'follow_text' => $follow_text
    ],
    icon_path: getUserFromId($_SESSION['user_id'])->getPictureUrl()
);

print($html);
