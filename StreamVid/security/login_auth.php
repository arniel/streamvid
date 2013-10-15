<?php
require_once('mysqli-streamvid_db.php');
require_once('password_hash.php');

if($result = $mysqli->query('SELECT * FROM registered_users WHERE username="' . $user . '"')){
    if($result->num_rows == 1){
        $userRow = $result->fetch_row();

        if(validate_password($pass, $userRow[2])){
            $_SESSION['user'] = $user;
            $_SESSION['pass'] = $pass;
            $_SESSION['class'] = $userRow[3];
            $_SESSION['prefs'] = unserialize($userRow[5]);
            $_SESSION['notif_read'] = $userRow[6];

            //load server messages before moving on

            if($result = $mysqli->query('SELECT * FROM messages')){
                $_SESSION['notifications'] = $result;
            }

            //Make sure prefs is entirely valid

            if(!isset($_SESSION['prefs']['myStarredShows'])){
                $_SESSION['prefs']['myStarredShows'] = array();
            }else if(!is_array($_SESSION['prefs']['myStarredShows'])){
                $_SESSION['prefs']['myStarredShows'] = array();
            }

            if(!isset($_SESSION['prefs']['autoPlay'])){
                $_SESSION['prefs']['autoPlay'] = 'true';
            }else if($_SESSION['prefs']['autoPlay'] != 'true' && $_SESSION['prefs']['autoPlay'] != 'false'){
                $_SESSION['prefs']['autoPlay'] = 'true';
            }

            if(!isset($_SESSION['prefs']['defaultFullscreen'])){
                $_SESSION['prefs']['defaultFullscreen'] = 'false';
            }else if($_SESSION['prefs']['defaultFullscreen'] != 'true' && $_SESSION['prefs']['defaultFullscreen'] != 'false'){
                $_SESSION['prefs']['defaultFullscreen'] = 'false';
            }

            if(!isset($_SESSION['prefs']['defaultTV'])){
                $_SESSION['prefs']['defaultTV'] = 'alphabetize';
            }else if($_SESSION['prefs']['defaultTV'] != 'alphabetize' && $_SESSION['prefs']['defaultTV'] != 'shuffle' && $_SESSION['prefs']['defaultTV'] != 'starred'){
                $_SESSION['prefs']['defaultTV'] = 'alphabetize';
            }

            if(!isset($_SESSION['prefs']['defaultPlayer'])){
                $_SESSION['prefs']['defaultPlayer'] = 'html5';
            }else if($_SESSION['prefs']['defaultPlayer'] != 'html5' && $_SESSION['prefs']['defaultPlayer'] != 'flash'){
                $_SESSION['prefs']['defaultPlayer'] = 'html5';
            }

            //special casing before normal page load

            if(isset($_GET['view']) && $userRow[3] == 'ADMN'){
                if($_GET['view'] == 'admincommand'){
                    include('admin_commands.php');
                    return;
                }
            }

            if(isset($_POST['command'])){
                include('streamvid_commands.php');
                return;
            }

            include($config['StreamVidLoc'] . 'template.php');
            return;
        }
    }
}

creds_fail();
return;