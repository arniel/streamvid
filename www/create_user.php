<?php
require('CONFIG FILE HERE');
require_once($config['StreamVidLoc'] . 'security/mysqli-streamvid_db.php');
require_once($config['StreamVidLoc'] . 'security/password_hash.php');

function askForCreds() {
    echo '<!DOCTYPE html><html><head><link rel="stylesheet" type="text/css" href="css/login.css"></head><body><div id="loginBox"><h1>New User Registration</h1><form method="POST" action="' . $_SERVER['REQUEST_URI'] . '" autocomplete="on"><table><tr><td>User:</td><td><input type="text" name="user"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" /><td></tr><tr><td colspan="2"><input type="submit" value="Register" /></td></tr></table></form></div></body></html>';
}

function isValidRegisterKey($mysqli, $key){
    $qValidKey = 'SELECT invite_key FROM invite_keys WHERE BINARY invite_key="' . $key . '"';

    if($result = $mysqli->query($qValidKey)){
        if($result->num_rows == 1){
            return true;
        }
    }

    return false;
}

function isValidNewUser($mysqli,$user,$key){
    $qUniqueUser = 'SELECT username FROM registered_users WHERE BINARY username="' . $user . '"';

    if($result = $mysqli->query($qUniqueUser)){
        if($result->num_rows == 0){
            $qUniqueRegisterKey = 'SELECT invite_id FROM registered_users WHERE BINARY invite_id="' . $key . '"';

            if($result = $mysqli->query($qUniqueRegisterKey)){
                if($result->num_rows == 0){
                    return true;
                }
            }
        }
    }

    return false;
}

function getNewUserStatus($mysqli,$key){
    $qNewUserStatus = 'SELECT privilege from invite_keys WHERE BINARY invite_key="' . $key . '"';

    if($result = $mysqli->query($qNewUserStatus)){
        $arr = $result->fetch_array();

        if($arr['privilege'] == 'USER'){
            return 'USER';
        }else if($arr['privilege'] == 'ADMN'){
            return 'ADMN';
        }else{
            return null;
        }
    }else{
        return null;
    }
}

function hashAndSaltPass($pass){
    $result = create_hash($pass);
    return $result;
}

function deleteKey($mysqli, $key){
    if($mysqli->query('DELETE FROM invite_keys WHERE invite_key="' . $key . '"') === TRUE){
        return true;
    }

    return false;
}

$prefs = array();
$prefs['myStarredShows'] = array();
$prefs['defaultTV'] = 'alphabetize';
$prefs['autoPlay'] = 'true';
$prefs['defaultFullscreen'] = 'false';

if(isset($_GET['rk'])){
   if(isValidRegisterKey($mysqli, $_GET['rk'])){
       if(isset($_POST['user']) && isset($_POST['pass'])){
           if(isValidNewUser($mysqli, $_POST['user'], $_GET['rk'])){
               if($privilege = getNewUserStatus($mysqli, $_GET['rk'])){
                   if($pass = hashAndSaltPass($_POST['pass'], $_GET['rk'])){
                       if(deleteKey($mysqli, $_GET['rk'])){
                           $qCreateUser = 'INSERT INTO registered_users (username, password, privilege, invite_id, prefs) VALUES ("' . $_POST['user'] . '", "' . $pass . '", "' . $privilege . '", "' . $_GET['rk'] . '", \'' . serialize($prefs) . '\')';

                           if($mysqli->query($qCreateUser) === TRUE){
                               echo '<html><head><script language="javascript">setTimeout(function(){ window.location.href = "index.php"; }, 3000);</script></head><body>Account creation successful, redirecting to main page in 3 seconds...</body></html>';
                           }else{
                               echo "Adding account was unsuccessful.";
                           }
                       }else{
                           echo "Adding account was unsuccessful.";
                       }
                   }else{
                       askForCreds();
                   }
               }else{
                   askForCreds();
               }
           }else{
               askForCreds();
           }
       }else{
           askForCreds();
       }
   }else{
       echo "404 Error: invalid state.";
       exit();
   }
}else{
    echo "404 Error: invalid state.";
    exit();
}