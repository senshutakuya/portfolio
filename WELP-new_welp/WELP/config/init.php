<?php

session_start();
//session_regenerate_id(true);

global $root, $root_url;
$root = $_SERVER['DOCUMENT_ROOT'] . '/../';
$root_url = (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . '/';

if((require($root . 'config/config.php'))['DEBUG_MODE'] == false){
    ini_set('display_errors', false);
}else{
    ini_set('display_errors', true);
}
