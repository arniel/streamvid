<?php
$config['StreamVidLoc'] = 'PATH NEEDED'; #WITH BEGINNING AND ENDING SLASHES, ABSOLUTE PATH TO STREAMVID INSTALLATION (SUCH AS /VAR/STREAMVID/)
$config['SessionSavePath'] = $config['StreamVidLoc'] . "sessions";
$config['LoginAuth'] = $config['StreamVidLoc'] . "login_auth.php";
$config['MYSQL_server'] = 'localhost';
$config['MYSQL_user'] = 'USERNAME NEEDED'; #<< ADD USERNAME FOR MYSQL DB HERE
$config['MYSQL_pass'] = 'PASSWORD NEEDED'; #<< ADD PASSWORD FOR MYSQL DB HERE
$config['MYSQL_userDB'] = 'user_db';
$config['MYSQL_movieDB'] = 'movie_db';
$config['LogFileLoc'] = $config['StreamVidLoc'] .'log.txt';
$config['debug'] = true;
$config['sickbeardDB'] = "/home/USERNAME NEEDED/.sickbeard/sickbeard.db"; #<< ADD USERNAME FOR LOCAL USER HERE (PATH MAY NEED TO CHANGE TOO, DEPENDING ON SICKBEARD CONFIG
$config['AuthTokenSecret'] = "PASSWORD NEEDED"; #<< SAME TEXT AS "AuthTokenSecret" DEFINED IN APACHE2.CONF
$config['AuthTokenPrefix'] = "/tvcontent/"; #WITH BEGINING AND ENDING SLASHES, SAME TEXT AS "AuthTokenPrefix" DEFINED IN APACHE2.CONF
$config['BaseTVDir'] = "PATH TO TV DIRECTORY"; #PATH TO THE TV DIRECTORY TO BE USED
$config['TMDbAPIKey'] = 'API KEY NEEDED'; #THE MOVIE DATABASE API KEY, REGISTER FOR ONE HERE: (http://docs.themoviedb.apiary.io/)
$config['MovieImageLoc'] = 'PATH TO MOVIE IMAGE DIRECTORY'; #PATH TO THE MOVIE IMAGES DIRECTORY TO BE USED
?>