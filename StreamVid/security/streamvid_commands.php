<?php
require('mysqli-streamvid_db.php');

function gatherPrefDataAndSubmit($mysqli){
    $prefString = serialize($_SESSION['prefs']);
    $mysqli->query('UPDATE registered_users SET prefs=\'' . $prefString . '\' WHERE username=\'' . $_SESSION['user'] . '\'');
}

function setUserPrefs($mysqli){
    if(isset($_POST['myStarredShows']) && isset($_POST['defaultTV']) && isset($_POST['autoPlay']) && isset($_POST['defaultFullscreen'])){

        $prefs = array();
        $prefs['myStarredShows'] = json_decode($_POST['myStarredShows']);
        $prefs['defaultTV'] = $_POST['defaultTV'];
        $prefs['autoPlay'] = $_POST['autoPlay'];
        $prefs['defaultFullscreen'] = $_POST['defaultFullscreen'];
        $prefs['defaultPlayer'] = $_POST['defaultPlayer'];

        $_SESSION['prefs'] = $prefs;

        gatherPrefDataAndSubmit($mysqli);
    }
}

function addStarredShow($showid, $mysqli){
    array_push($_SESSION['prefs']['myStarredShows'], $showid);
    gatherPrefDataAndSubmit($mysqli);
}

function removeStarredShow($showid, $mysqli){
    foreach($_SESSION['prefs']['myStarredShows'] as $i=>$id){
        if($id == $showid){
            unset($_SESSION['prefs']['myStarredShows'][$i]);
            gatherPrefDataAndSubmit($mysqli);
            break;
        }
    }
}

function submitNewNotifRead($mysqli){
    $time = time();
    $mysqli->query('UPDATE registered_users SET notif_read="' . $time  . '" WHERE username="' . $_SESSION['user'] . '"');
}

function submitNewMessage($title, $body, $mysqli){
    if($_SESSION['class'] == 'ADMN'){
        if($result = $mysqli->query('SELECT * FROM messages')){
            if($result->num_rows == null){
                $newSQLString = 'INSERT INTO messages (msg_id, title, body, date) VALUES ("0", "' . $mysqli->escape_string($title) . '", "' . $mysqli->escape_string($body) . '", "' . time() . '");';

                $mysqli->query($newSQLString);
            }else{
                if($result->num_rows > 9){
                    //limit the message queue to 10 messages
                    //$messageArr = array_slice($result, 0, 9);

                    $numPossibleMessages = 9; //add the newest message to make a round 10
                    $newerMsgCount = 0;
                    foreach($result as $i=>$newerMsg){
                        if($newerMsgCount == $numPossibleMessages)
                            break;

                        $messageArr[$i] = $newerMsg;
                        $newerMsgCount++;
                    }
                }else{
                    $messageArr = $result;
                }

                $newSQLString = 'TRUNCATE TABLE messages;';
                $newSQLString .= 'INSERT INTO messages (msg_id, title, body, date) VALUES ("1", "' . $mysqli->escape_string($title) . '", "' . $mysqli->escape_string($body) . '", "' . time() . '");';
                $count = 2;

                foreach($messageArr as $msg){
                    $newSQLString .= 'INSERT INTO messages (msg_id, title, body, date) VALUES ("' . $mysqli->escape_string($count) . '", "' . $mysqli->escape_string($msg['title']) . '", "' . $mysqli->escape_string($msg['body']) . '", "' . $msg['date'] . '");';
                    $count++;
                }

                $mysqli->multi_query($newSQLString);
            }
        }
    }
}

function submitStaffPicksTV($staffPicks, $mysqli){
    $newSQLString = 'TRUNCATE TABLE staff_picks;';

    foreach($staffPicks as $tvdbid){
        $newSQLString .= 'INSERT INTO staff_picks (tvdbid) VALUES ("' . $tvdbid . '");';
    }

    $mysqli->multi_query($newSQLString);
}

function submitStaffPicksMovies($staffPicks, $mysqli_movies){
    $newSQLString = 'TRUNCATE TABLE staff_picks;';

    foreach($staffPicks as $movie){
        $newSQLString .= 'INSERT INTO staff_picks (movieid, poster, title) VALUES (' . $movie['movieid'] . ', "' . $movie['poster'] . '", "' . $movie['title'] . '");';
    }

    $mysqli_movies->multi_query($newSQLString);
}

function submitNewMovie($title, $release_date, $imdb_id, $runtime, $overview, $poster, $filename, $mysqli_movies, $movieImageLoc){
    $posterFilename = $movieImageLoc . $imdb_id . '.jpg';
    copy($poster, $posterFilename);

    $newSQLString = 'INSERT INTO metadata (id, imdb_id, title, release_date, runtime, overview, poster, filename) VALUES (NULL, "' . $imdb_id . '", "' . $mysqli_movies->escape_string($title) . '", "' . $release_date . '", ' . $runtime . ', "' . $mysqli_movies->escape_string($overview) . '", "' . $mysqli_movies->escape_string($posterFilename) . '", "' . $mysqli_movies->escape_string($filename) . '")';

    $mysqli_movies->query($newSQLString);
}

function deleteMovie($movieid, $mysqli_movies){
    $newSQLString = 'DELETE FROM metadata WHERE id="' . $movieid . '"';

    $mysqli_movies->query($newSQLString);
}

if(isset($_POST['command'])){
    switch ($_POST['command']){
        case 'submitPrefs':
            setUserPrefs($mysqli);
            break;
        case 'addStarredShow':
            if(isset($_POST['starredShow'])){
                addStarredShow($_POST['starredShow'], $mysqli);
            }
            break;
        case 'removeStarredShow':
            if(isset($_POST['starredShow'])){
                removeStarredShow($_POST['starredShow'], $mysqli);
            }
            break;
        case 'submitNewNotifRead':
            submitNewNotifRead($mysqli);
            break;
        case 'submitNewMessage':
            if(isset($_POST['title']) && isset($_POST['body'])){
                submitNewMessage($_POST['title'], $_POST['body'], $mysqli);
            }
            break;
        case 'submitStaffPicksTV':
            if(isset($_POST['staffPicks'])){
                submitStaffPicksTV(json_decode($_POST['staffPicks']), $mysqli);
            }
            break;
        case 'submitStaffPicksMovies':
            if(isset($_POST['staffPicks'])){
                submitStaffPicksMovies($_POST['staffPicks'], $mysqli_movies);
            }
            break;
        case 'submitNewMovie':
            if(isset($_POST['title']) && isset($_POST['release_date']) && isset($_POST['imdb_id']) && isset($_POST['runtime']) && isset($_POST['overview']) && isset($_POST['poster']) && isset($_POST['filename'])){
                submitNewMovie($_POST['title'], $_POST['release_date'], $_POST['imdb_id'], $_POST['runtime'], $_POST['overview'], $_POST['poster'], $_POST['filename'], $mysqli_movies, $config['MovieImageLoc']);
            }
            break;
        case 'deleteMovie':
            if(isset($_POST['movieid'])){
                deleteMovie($_POST['movieid'], $mysqli_movies);
            }
            break;
        default:
            break;
    }
}