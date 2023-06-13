<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/post.php');

function getFormattedCreatedAt(int $post_id) : String
{

    $post = getPost($post_id);
    $now = new DateTime();
    $created_at = new DateTime($post->getCreatedAt());
    $diff = date_diff($now, $created_at, true);

    if($diff->format('%y')!= 0){
        $ago = $diff->format('%y') . '年前';
    }else if($diff->format('%m') != 0){
        $ago = $diff->format('%m') . 'ヶ月前';
    }else if($diff->format('%d') != 0){
        $ago = $diff->format('%d') . '日前';
    }else if($diff->format('%h') != 0){
        $ago = $diff->format('%h') . '時間前';
    }else if($diff->format('%i') != 0){
        $ago = $diff->format('%i') . '分前';
    }else{
        $ago = $diff->format('%s') . '秒前';
    }

    return $ago;

}

function createPost(int $post_id, int $user_id) : String
{
    global $root, $root_url;
    $html = file_get_contents($root . 'templates/home/post_box.html');
    $post = getPost($post_id);
    $author = $post->getUser();
    $iconPath = 'https://pics.prcm.jp/654b637d854c5/84936407/png/84936407.png';

    if($author != null){
        $name = $author->getName();
        if($author->getPictureId() != null){
            $iconPath = $root_url . 'home/icon.php?id=' . $author->getPictureId();
        }
    }else{
        $name = '(消去されたユーザー)';
    }

    $ago = getFormattedCreatedAt($post_id);

    if(getUserFromId($user_id)->isFavorite($post->getId())){
        $fill = 'red';
    }else{
        $fill = 'none';
    }

    $keywords = [
        'icon_path' => $iconPath,
        'name' => $name,
        'created_at' => $ago,
        'content' => $post->getContent(),
        'favos' => $post->getFavorites(),
        'post_id' => $post->getId(),
        'replies' => count($post->getReplies()),
        'favorite_fill' => $fill,
        'user_id' => $author->getId()
    ];

    foreach($keywords as $key => $val){
        $html = str_replace('{{' . $key . '}}', htmlspecialchars($val, ENT_QUOTES), $html);
    }

    return $html;

}

