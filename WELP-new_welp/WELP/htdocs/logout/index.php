<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '../../config/init.php');
require_once($root . 'functions/auth.php');

logout();
header('Location: ' . $root_url . 'login');
