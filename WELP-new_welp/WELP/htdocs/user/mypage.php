<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/../config/init.php');
require_once($root . 'functions/common.php');
require_once($root . 'functions/user.php');
require_once($root . 'config/login_required.php');

$user = getUserFromId($_SESSION['user_id']);

$html = create_page(
    $root . 'templates/user/mypage.html',
    'マイページ',
    [],
    []
);

print($html);