<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');

if(isset($_SESSION['user_id'])){
    header('Location: ' . $root_url . 'home');
    exit;
}else{
    header('Location: ' . $root_url . 'login');
    exit;
}
