<?php

if(empty($_POST['post_id'])){
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/user.php');
require_once($root . 'functions/post.php');

header('Content-type: application/json');

$user = getUserFromId($_SESSION['user_id']);
$post_id = $_POST['post_id'];

if($user->isFavorite($post_id)){
    $user->unFavorite($post_id);
    $mes = false;
}else{
    $user->favorite($post_id);
    $mes = true;
}

$favos = getPost($post_id)->getFavorites();

echo json_encode([$mes, $post_id, $favos]);
exit;
