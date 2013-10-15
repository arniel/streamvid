<?php

require_once('mysqli-streamvid_db.php');

if(isset($_GET['newKey'])){
    if($_GET['newKey'] != 'ADMN' && $_GET['newKey'] != 'USER'){
        echo '<html><head><script language="javascript">setTimeout(function(){ window.location.href = "index.php?view=admin"; }, 1000);</script></head><body>Key not successfully generated (wrong key setting)</body></html>';
        return;
    }

    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $key = '';

    for($i = 0; $i < 100; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];

    $qInsertKey = 'INSERT INTO invite_keys (invite_key, privilege) VALUES ("' . $key . '", "' . $_GET['newKey'] . '")';

    if(TRUE === $mysqli->query($qInsertKey)){
        echo 'Key successfully generated.<br/>';
        echo 'Referring URL is:  https://' . $_SERVER[HTTP_HOST] . '/create_user.php?rk=' . $key;
        echo '<br/><a href="create_user.php?rk=' . $key . '">Link Here</a>';
        echo '<br/><br/>';
        return;
    }else{
        echo '<html><head><script language="javascript">setTimeout(function(){ window.location.href = "index.php?view=admin"; }, 1000);</script></head><body>Key not successfully generated</body></html>';
        return;
    }
}else if(isset($_GET['deleteKey'])){
    if($result = $mysqli->query('SELECT invite_key FROM invite_keys WHERE invite_key="' . $_GET['deleteKey'] . '"')){
        if($result->num_rows == 1){
            if(TRUE === $mysqli->query('DELETE FROM invite_keys WHERE invite_key="' . $_GET['deleteKey'] . '"')){
                echo '<html><head><script language="javascript">setTimeout(function(){ window.location.href = "index.php?view=admin"; }, 1000);</script></head><body>Key successfully deleted.</body></html>';
                return;
            }
        }
    }

    echo '<html><head><script language="javascript">setTimeout(function(){ window.location.href = "index.php?view=admin"; }, 1000);</script></head><body>Key not successfully deleted.</body></html>';
    echo '<br/><br/>';
    return;
}else if(isset($_GET['deleteUser'])){
    if($result = $mysqli->query('SELECT user_id FROM registered_users WHERE user_id="' . $_GET['deleteUser'] . '"')){
        if($result->num_rows == 1){
            if(TRUE === $mysqli->query('DELETE FROM registered_users WHERE user_id="' . $_GET['deleteUser'] . '"')){
                echo '<html><head><script language="javascript">setTimeout(function(){ window.location.href = "index.php?view=admin"; }, 1000);</script></head><body>User successfully deleted.</body></html>';
                return;
            }
        }
    }

    echo '<html><head><script language="javascript">setTimeout(function(){ window.location.href = "index.php?view=admin"; }, 1000);</script></head><body>User not successfully deleted.</body></html>';
    echo '<br/><br/>';
    return;
}else if(isset($_GET['runstats'])){
    exec('python ' . $config['StreamVidLoc'] .  'stats.py');
    echo '<html><head><script language="javascript">setTimeout(function(){ window.location.href = "index.php?view=admin"; }, 1000);</script></head><body>Stats script successfully run</body></html>';
    return;
}