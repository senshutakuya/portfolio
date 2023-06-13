<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'classes/database.php');
require_once($root . 'functions/post.php');

/**
 * 投稿に関する関数のファイル
 * 
 * @version 1.0
 * @author 吉田 健人
 * 
 */

class Post
{
    private int $id;
    private ?User $user;
    private ?Post $from_post;
    private string $content;
    private $created_at;

    public function __construct($id, $user, $from_post, $content, $created_at)
    {
        $this->id = $id;
        $this->user = $user;
        $this->from_post = $from_post;
        $this->content = $content;
        $this->created_at = $created_at;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getUser() : ?User
    {
        return $this->user;
    }

    public function getFromPost() : Post
    {
        return $this->from_post;
    }

    public function getContent() : String
    {
        return $this->content;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getFavorites() : int
    {
        $db = new Database();
        $db->setSQL('SELECT COUNT(*) FROM `favorites` WHERE `post_id` LIKE ?');
        $db->setBindArray([$this->id]);
        $db->execute();
        $res = $db->fetch();
        return $res[0];
    }

    public function getReplies() : array
    {
        $posts = [];
        $db = new Database();
        $db->setSQL('SELECT `id` FROM `posts` WHERE `from_post_id` = ? ORDER BY `created_at` DESC');
        $db->setBindArray([$this->id]);
        $db->execute();
        $res = $db->fetchAll();
        foreach($res as $post){
            $posts[] = getPost($post['id']);
        }
        return $posts;
    }

    public function reply(String $content)
    {

    }

}