<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'classes/database.php');
require_once($root . 'classes/post.php');
require_once($root . 'functions/user.php');

function deletePost(Post $post) : void
{
    $db = new Database();
    $db->setSQL('DELETE FROM `posts` WHERE `id` = ?;');
    $db->setBindArray([$post->getId()]);
    $db->execute();
}

function getPost(int $id) : ?Post
{
    $db = new Database();
    $db->setSQL('SELECT * FROM `posts` WHERE `id` = ?;');
    $db->setBindArray([$id]);
    $db->execute();
    $post = $db->fetch();

    if(!$post){
        return null;
    }

    if($post['from_post_id'] != null){
        $from_post = getPost($post['from_post_id']);
    }else{
        $from_post = null;
    }

    return new Post(
        $post['id'],
        getUserFromId($post['user_id']),
        $from_post,
        $post['content'],
        $post['created_at']
    );
}

function searchPosts(string $keyword = null, User $user = null, bool $reply = true, int $amount = 1) : array
{

    $resultPosts = [];
    $bindArrays = [];

    if($keyword != null){
        $query = ' WHERE `content` LIKE "%' . htmlspecialchars($keyword) . '%"';
    }

    if($user != null){
        if(empty($query)){
            $query = ' WHERE `user_id` = ' . $user->getId();
        }else{
            $query = $query . ' AND `user_id` = ' . $user->getId();
        }
    }

    if(!$reply){
        if(empty($query)){
            $query = ' WHERE `from_post_id` IS NULL';
        }else{
            $query = $query . ' AND `from_post_id` IS NULL';
        }
    }

    if(empty($query)){
        $query = ' ';
    }

    $db = new Database();
    $sql = 'SELECT * FROM `posts`' . $query . ' ORDER BY `created_at` DESC LIMIT ' . $amount;
    $db->setSQL($sql);
    $db->setBindArray($bindArrays);
    $db->execute();
    $result = $db->fetchAll();

    if(!$result){
        return $resultPosts;
    }

    foreach($result as $post){
        if($post['from_post_id'] != null){
            $from_post = getPost($post['from_post_id']);
        }else{
            $from_post = null;
        }
        $resultPosts[] = new Post(
            $post['id'],
            getUserFromId($post['user_id']),
            $from_post,
            $post['content'],
            $post['created_at']
        );
    }

    return $resultPosts;

}

function sendPost(User $user, ?Post $from_post, String $content) : void
{
    if($from_post != null){
        $from_post_id = $from_post->getId();
    }else{
        $from_post_id = null;
    }

    $db = new Database();
    $db->setSQL('INSERT INTO `posts` (`user_id`, `from_post_id`, `content`) VALUES (:user_id, :from_post_id, :content);');
    $db->setBindArray([
        'user_id' => $user->getId(),
        'from_post_id' => $from_post_id,
        'content' => $content
    ]);
    $db->execute();
}