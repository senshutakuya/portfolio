<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/user.php');

if (empty($_SESSION['user_id']) or getUserFromId($_SESSION['user_id']) == null){
    header('Location: /login');
    exit;
}
