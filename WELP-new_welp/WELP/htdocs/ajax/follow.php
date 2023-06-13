<?php

header('Content-type: application/json');

if(empty($_POST['target_user_id'])){
    echo json_encode([]);
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/user.php');

$client_user = getUserFromId($_SESSION['user_id']);
$target_user = getUserFromId($_POST['target_user_id']);

if($client_user == null or $target_user == null){
    echo json_encode([]);
    exit;
}

if($client_user->isFollow($target_user)){
    $client_user->unfollow($target_user);
    $bool = false;
}else{
    $client_user->Follow($target_user);
    $bool = true;
}

$following = $target_user->getFollowing();
$followers = $target_user->getFollower();

echo json_encode([$bool, $following, $followers]);
exit;
