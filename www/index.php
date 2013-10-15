<?php
require('CONFIG FILE HERE');

ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);
# Session lifetime of 7 days
ini_set('session.gc_maxlifetime', 7*24*60*60);
session_set_cookie_params(7*24*60*60); // set session to last for 7 days

# Enable session garbage collection with a 1% chance of
# running on each session_start()
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1000);

# Our own session save path; it must be outside the
# default system save path so Debian's cron job doesn't
# try to clean it up. The web server daemon must have
# read/write permissions to this directory.
session_save_path($config['SessionSavePath']);

# Start the session
session_start();

if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);

    echo '<html><head><script language="javascript">setTimeout(function(){ window.location.href = "index.php"; }, 3000);</script></head><body>Logout successful, redirecting in 3 seconds...</body></html>';
    return;
}

function creds_fail(){
    if(isset($_SESSION))
        session_destroy();

    echo '<!DOCTYPE html><html><head><title>AB3 | Login</title><link rel="stylesheet" type="text/css" href="css/login.css"></head><body><div id="loginBox"><h1>Please login...</h1><form method="POST" action="' . $_SERVER['REQUEST_URI'] . '" autocomplete="on"><table><tr><td>User:</td><td><input type="text" name="user"></td></tr><tr><td>Password:</td><td><input type="password" name="pass" /><td></tr><tr><td colspan="2"><input type="submit" value="Submit" /></td></tr></table></form><br/><br/>Think you need access? <a href="mailto:curfewserver@gmail.com">Email us.</a></div></body></html>';
}

$user = '';
$pass = '';

if(isset($_POST['user']) && isset($_POST['pass'])){
    $user = $_POST['user'];
    $pass = $_POST['pass'];
}else if(isset($_SESSION['user']) && isset($_SESSION['pass'])){
    $user = $_SESSION['user'];
    $pass = $_SESSION['pass'];
}else{
    creds_fail();
    return;
}

include($config['LoginAuth']);