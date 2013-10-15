<?php
$mysqli = new mysqli($config['MYSQL_server'],$config['MYSQL_user'],$config['MYSQL_pass'],$config['MYSQL_userDB']);

if($mysqli->connect_errno){
    printf("Connect to MYSQL server failed: %s\n", $mysqli->connect_error);
    exit();
}

$mysqli_movies = new mysqli($config['MYSQL_server'],$config['MYSQL_user'],$config['MYSQL_pass'],$config['MYSQL_movieDB']);

if($mysqli_movies->connect_errno){
    printf("Connect to MYSQL server failed: %s\n", $mysqli_movies->connect_error);
    exit();
}