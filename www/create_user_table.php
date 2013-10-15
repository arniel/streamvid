<?php
require('CONFIG FILE HERE');
$mysqli = new mysqli($config['MYSQL_server'],$config['MYSQL_user'],$config['MYSQL_pass']);

if($mysqli->connect_errno){
    printf("Connect to MYSQL server failed: %s\n", $mysqli->connect_error);
    exit();
}

if($mysqli->query('CREATE DATABASE IF NOT EXISTS ' . $config['MYSQL_userDB'] . ';') === TRUE){
    echo "Database " . $config['MYSQL_userDB'] . " created successfully</br>";
}else{
    echo "Error creating database: " . $mysqli->error;
}

if($mysqli->query('CREATE TABLE IF NOT EXISTS ' . $config['MYSQL_userDB'] . '.registered_users (
	user_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	username VARCHAR(40) NOT NULL,
	password TEXT NOT NULL,
	privilege VARCHAR(4) NOT NULL DEFAULT "USER",
	invite_id VARCHAR(100) NOT NULL,
	prefs TEXT NOT NULL,
	notif_read INT NOT NULL DEFAULT  "0",
	PRIMARY KEY (user_id),
	UNIQUE INDEX username (username),
	UNIQUE INDEX invite_id (invite_id)
)') === TRUE){
    echo 'Table "' . $config['MYSQL_userDB'] . '.registered_users" created successfully</br>';

    if(TRUE === $mysqli->query('INSERT INTO ' . $config['MYSQL_userDB'] . '.registered_users (username, password, privilege, invite_id, prefs) VALUES ("default", "sha1:1000:FY/VqbWoSVGBZSQAAF9w50r5E30sLMK8:y7zcoZToaxdWrai787Pla2Xd7NVkYLpo", "ADMN", "default", "")')){
        echo 'Default user "default" with password "default" added to ' . $config['MYSQL_userDB'] . '<br/>';
    }else{
        echo 'Error creating default user: ' . $mysqli->error;
    }
}else{
    echo 'Error creating table: ' . $mysqli->error;
}

if($mysqli->query('CREATE TABLE IF NOT EXISTS ' . $config['MYSQL_userDB'] . '.invite_keys (
	invite_key VARCHAR(100) NOT NULL,
	privilege VARCHAR(4) NOT NULL DEFAULT "USER",
	UNIQUE INDEX invite_key (invite_key)
    )') === TRUE){
    echo 'Table "' . $config['MYSQL_userDB'] . '.invite_keys" created successfully</br>';
}else{
    echo 'Error creating table: ' . $mysqli->error;
}

if($mysqli->query('CREATE TABLE IF NOT EXISTS ' . $config['MYSQL_userDB'] . '.messages (
	msg_id SMALLINT(5) UNSIGNED NOT NULL,
	title VARCHAR(25) NOT NULL,
	body VARCHAR(210) NOT NULL,
	date INT NOT NULL,
	UNIQUE INDEX msg_id (msg_id)
    )') === TRUE){
    echo 'Table "' . $config['MYSQL_userDB'] . '.messages" created successfully</br>';
}else{
    echo 'Error creating table: ' . $mysqli->error;
}

if($mysqli->query('CREATE TABLE IF NOT EXISTS ' . $config['MYSQL_userDB'] . '.staff_picks (
	tvdbid INT NOT NULL,
    )') === TRUE){
    echo 'Table "' . $config['MYSQL_userDB'] . '.staff_picks" created successfully</br>';
}else{
    echo 'Error creating table: ' . $mysqli->error;
}

if($mysqli->query('CREATE DATABASE IF NOT EXISTS ' . $config['MYSQL_movieDB'] . ';') === TRUE){
    echo "Database " . $config['MYSQL_movieDB'] . " created successfully</br>";
}else{
    echo "Error creating database: " . $mysqli->error;
}

if($mysqli->query('CREATE TABLE IF NOT EXISTS ' . $config['MYSQL_movieDB'] . '.metadata (
    id smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    imdb_id tinytext NOT NULL,
    title text NOT NULL,
    release_date tinytext NOT NULL,
    runtime smallint(5) unsigned NOT NULL,
    overview text NOT NULL,
    poster text NOT NULL,
    filename tinytext NOT NULL,
    PRIMARY KEY (id)
    )') === TRUE){
    echo 'Table "' . $config['MYSQL_movieDB'] . '.metadata" created successfully</br>';
}else{
    echo 'Error creating table: ' . $mysqli->error;
}

if($mysqli->query('CREATE TABLE IF NOT EXISTS ' . $config['MYSQL_movieDB'] . '.staff_picks (
    movieid int(11) NOT NULL,
    poster text NOT NULL,
    title text NOT NULL
    )') === TRUE){
    echo 'Table "' . $config['MYSQL_movieDB'] . '.staff_picks" created successfully</br>';
}else{
    echo 'Error creating table: ' . $mysqli->error;
}