<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '../../config/init.php');
require_once($root . 'classes/database.php');

if(empty($_GET['id'])){
    exit;
}

$db = new Database();
$db->setSQL('SELECT * FROM `images` WHERE `id` = ?');
$db->setBindArray([$_GET['id']]);
$db->execute();
$res = $db->fetch();

if(!$res){
    exit;
}

header('Content-type: ' . $res['image_type']);
echo $res['image_content'];
