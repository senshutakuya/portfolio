<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'classes/database.php');

class User
{
    private int $id;
    private string $name;
    private string $email;
    private ?int $picture_id;
    private bool $is_admin;
    private string $created_at;
    private string $updated_at;

    public function __construct(int $id, string $name, string $email, ?int $picture_id, bool $is_admin, string $created_at, string $updated_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->picture_id = $picture_id;
        $this->is_admin = $is_admin;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public function isAdmin() : bool
    {
        return $this->is_admin;
    }

    public function favorite(int $post_id) : void
    {
        $db = new Database();
        $db->setSQL('INSERT IGNORE INTO `favorites` (`user_id`, `post_id`) VALUES (?, ?);');
        $db->setBindArray([$this->id, $post_id]);
        $db->execute();
    }

    public function isFavorite(int $post_id) : bool
    {
        $db = new Database();
        $db->setSQL('SELECT `id` FROM `favorites` WHERE `user_id` = ? AND `post_id` = ?;');
        $db->setBindArray([$this->id, $post_id]);
        $db->execute();
        $res = $db->fetch();

        if(!$res){
            return false;
        }else{
            return true;
        }
    }

    public function unFavorite(int $post_id) : void
    {
        $db = new Database();
        $db->setSQL('DELETE FROM `favorites` WHERE `user_id` = ? AND `post_id` = ?;');
        $db->setBindArray([$this->id, $post_id]);
        $db->execute();
    }

    public function follow(User $to_user) : void
    {
        $db = new Database();
        $db->setSQL('INSERT INTO `follows` (`from_id`, `to_id`) VALUES (?, ?)');
        $db->setBindArray([$this->id, $to_user->getId()]);
        $db->execute();
    }

    public function isFollow(User $to_user) : bool
    {
        $db = new Database();
        $db->setSQL('SELECT `id` FROM `follows` WHERE `from_id` = ? AND `to_id` = ?');
        $db->setBindArray([$this->id, $to_user->getId()]);
        $db->execute();
        $res = $db->fetch();
        if(!$res){
            return false;
        }else{
            return true;
        }
    }

    public function unFollow(User $to_user) : void
    {
        $db = new Database();
        $db->setSQL('DELETE FROM `follows` WHERE `from_id` = ? AND `to_id` = ?');
        $db->setBindArray([$this->id, $to_user->getId()]);
        $db->execute();
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function getFollowing() : int
    {
        $db = new Database();
        $db->setSQL('SELECT `id` FROM `follows` WHERE `from_id` = ?;');
        $db->setBindArray([$this->id]);
        $db->execute();
        $res = $db->fetchAll();
        return count($res);
    }

    public function getFollower() : int
    {
        $db = new Database();
        $db->setSQL('SELECT `id` FROM `follows` WHERE `to_id` = ?;');
        $db->setBindArray([$this->id]);
        $db->execute();
        $res = $db->fetchAll();
        return count($res);
    }

    public function getPictureId() : ?int
    {
        return $this->picture_id;
    }

    public function getPictureUrl() : string
    {
        $picture_id = $this->getPictureId();
        if($picture_id != null){
            return '/home/icon.php?id=' . $picture_id;
        }else{
            return 'https://pics.prcm.jp/654b637d854c5/84936407/png/84936407.png';
        }
    }

    public function getCreatedAt() : string
    {
        return $this->created_at;
    }

    public function getUpdatedAt() : string
    {
        return $this->updated_at;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
        $this->save();
    }

    public function setEmail(string $email) : void
    {
        $this->email = $email;
        $this->save();
    }

    public function setPictureId(int $picture_id) : void
    {
        $this->picture_id = $picture_id;
        $this->save();
    }

    public function save() : void
    {
        $db = new Database();
        $db->setSQL('UPDATE `users` SET `name` = :name, `email` = :email, `picture_id` = :picture_id, `updated_at` = NOW() WHERE `id` = :id;');
        $db->setBindArray([
            'name' => $this->name,
            'email' => $this->email,
            'picture_id' => $this->picture_id,
            'id' => $this->id
        ]);
        $db->execute();
    }
}
