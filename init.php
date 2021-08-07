<?php
require('config/db.php');
session_start();

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, "utf8");

