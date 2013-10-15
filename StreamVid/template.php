<?php
require($config['StreamVidLoc'] . 'sickbeard_db.php');

$logFile = file_get_contents($config['LogFileLoc']);
$logFile .= date('Y-m-d H:i:s') . ",$_SERVER[REMOTE_ADDR]," . $user . "\n";
file_put_contents($config['LogFileLoc'], $logFile);

$db = new SickBeard_DB($config['sickbeardDB']);

if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    if(isset($_GET['view'])){
        switch ($_GET['view']){
            case 'home':
                echo '<title>AB3 | Home</title>';
                break;
            case 'movies':
                echo '<title>AB3 | Movies</title>';
                break;
            case 'film':
                echo '<title>AB3 | Movie</title>';
                break;
            case 'tv':
                echo '<title>AB3 | TV</title>';
                break;
            case 'user':
                echo '<title>AB3 | User Panel</title>';
                break;
            case 'admin':
                echo '<title>AB3 | Admin Panel</title>';
                break;
            case 'show':
                echo '<title>AB3 | Show</title>';
                break;
            case 'episode':
                echo '<title>AB3 | Episode</title>';
                break;
            case 'search':
                echo '<title>AB3 | Search</title>';
                break;
            default:
                //require('view_featured.php');
        }
    }else{
        echo '<title>AB3 | Home</title>';
    }
    ?>
    <link rel="stylesheet" href="css/index.css" type="text/css"/>
    <script src='js/jquery.js' type='text/javascript'></script>
    <script type="text/javascript" src="js/jwplayer.js"></script>
    <link rel="shortcut icon" href="http://198.27.82.95/favicon.ico?v=2" />
</head>
<body>
<div id="siteContainer">
    <table id="siteContent">
        <tr id="header">
            <th class="column1">
                <img id="siteTitle" src="images/SiteTitle.png" />
            </th>
            <th class="column2">
                <div id="searchBox">
                    <?php
                    $lookNotesNeeded = false;

                    if($_SESSION['notif_read'] == 'NEVER'){
                        $lookNotesNeeded = true;
                    }else{
                        $time = $_SESSION['notif_read'];

                        foreach($_SESSION['notifications'] as $row){
                            if($_SESSION['notif_read'] < $row['date']){
                                $lookNotesNeeded = true;
                                break;
                            }
                        }
                    }
                    ?>
                    <img id="logout" src="images/icon_logout.png"  onmouseover="this.src='images/icon_logout_hover.png'" onmouseout="this.src='images/icon_logout.png'"/>
                    <? if($lookNotesNeeded) { ?>
                    <img id="notes"  data-state="looknotes" src="images/icon_looknotes.png"  onmouseover="this.src='images/icon_looknotes_hover.png'" onmouseout="this.src='images/icon_looknotes.png'"/>
                    <? } else { ?>
                    <img id="notes" data-state="notes" src="images/icon_notes.png"  onmouseover="this.src='images/icon_notes_hover.png'" onmouseout="this.src='images/icon_notes.png'"/>
                    <? } ?>
                    <img id="searchIcon" src="images/icon_search.png"  onmouseover="this.src='images/icon_search_hover.png'" onmouseout="this.src='images/icon_search.png'"/>
                    <input type="text"/>
                </div>
                <div style="display: none;" id="notesPanel">
                    <?php
                    foreach($_SESSION['notifications'] as $row){
                        if($_SESSION['notif_read'] < $row['date']){
                            echo '<div class="message active"><h4>' . $row['title'] . '</h4><p>' . $row['body'] . '</p></div>';
                        }else {
                            echo '<div class="message"><h4>' . $row['title'] . '</h4><p>' . $row['body'] . '</p></div>';
                        }
                    }
                    ?>
                </div>
            </th>
        </tr>
        <tr id="body">
            <td class="column1" id="navigationPane"><?php require($config['StreamVidLoc'] . 'navigation.php'); ?></td>
            <td rowspan="2" class="column2" id="mainContentPane">
                <?php
                if(isset($_GET['view'])){
                    switch ($_GET['view']){
                        case 'home':
                            require($config['StreamVidLoc'] . 'view_featured.php');
                            break;
                        case 'tv':
                            require($config['StreamVidLoc'] . 'view_tv.php');
                            break;
                        case 'movies':
                            require($config['StreamVidLoc'] . 'view_movies.php');
                            break;
                        case 'film':
                            require($config['StreamVidLoc'] . 'view_film.php');
                            break;
                        case 'user':
                            require($config['StreamVidLoc'] . 'view_user.php');
                            break;
                        case 'admin':
                            if($userRow[3] == 'ADMN'){
                                if(isset($_GET['addmovie'])){
                                    require($config['StreamVidLoc'] . 'addmovie.php');
                                }else{
                                    require($config['StreamVidLoc'] . 'view_admin.php');
                                }
                            }else{
                                require($config['StreamVidLoc'] . 'view_featured.php');
                            }
                            break;
                        case 'show':
                            require($config['StreamVidLoc'] . 'view_show.php');
                            break;
                        case 'episode':
                            require($config['StreamVidLoc'] . 'view_episode.php');
                            break;
                        case 'search':
                            require($config['StreamVidLoc'] . 'search.php');
                            break;
                        default:
                            //require($config['StreamVidLoc'] . 'view_featured.php');
                    }
                }else{
                    require($config['StreamVidLoc'] . 'view_featured.php');
                }
                ?>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
        <tr id="footer">
            <td colspan="2">
                <br/>
                <span>StreamVid v1.1, Powered by CurfewDesign</span>
                <br/>
                <br/>
                <br/>
            </td>
        </tr>
    </table>
</div>
</body>
<script type="text/javascript">
    $('img#siteTitle').click(function(){
        window.location.href = 'index.php';
    });

    var textBoxActive = false;

    $('#searchBox #searchIcon').click(function(){
        $('#searchBox input').focus();
        textBoxActive = true;
        $('#searchBox').addClass('hover');
    });

    var notes = false;
    $('#searchBox #notes').click(function(){
        if(notes){
            $(this).attr('src', 'images/icon_notes.png');
            $(this).attr('onmouseover', 'this.src="images/icon_notes_hover.png"');
            $(this).attr('onmouseout', 'this.src="images/icon_notes.png"');

            if($(this).data('state') == 'looknotes'){
                $('#notesPanel .message').removeClass('active');
            }

            $('#notesPanel').slideUp(300);

            if(!textBoxActive)
                setTimeout(function(){
                    notes = false;
                    $('#searchBox').removeClass('hover');
                }, 1000);
        }else{
            if($(this).data('state') == 'looknotes'){
                $.post("index.php", { command: 'submitNewNotifRead' });
                $(this).data('state', 'notes');

                $(this).attr('src', 'images/icon_looknotes_active.png');
                $(this).attr('onmouseover', 'this.src="images/icon_looknotes_active.png"');
                $(this).attr('onmouseout', 'this.src="images/icon_looknotes_active.png"');
            }else{
                $(this).attr('src', 'images/icon_notes_active.png');
                $(this).attr('onmouseover', 'this.src="images/icon_notes_active.png"');
                $(this).attr('onmouseout', 'this.src="images/icon_notes_active.png"');
            }

            $('#notesPanel').slideDown(300);
            $('#searchBox').addClass('hover');
            notes = true;
        }
    });

    $('#searchBox #logout').click(function(){
        window.location.href= 'index.php?logout=true';
    });

    $('#searchBox').mouseover(function(){
        if(!textBoxActive)
            $(this).addClass('hover');
    });

    $('#searchBox').mouseout(function(){
        if(!textBoxActive && !notes)
            $(this).removeClass('hover');
    });

    $('#searchBox input').focus(function(){
        textBoxActive = true;

        $('#searchBox').addClass('hover');
    });

    $('#searchBox input').blur(function(){
        textBoxActive = false;

        if(!notes)
            $('#searchBox').removeClass('hover');
    });

    $('#searchBox input').keypress(function (e) {
        if (e.which == 13) {
            window.location.href = 'index.php?view=search&q=' + $(this).val();
        }
    });

    $(document).ready(function(){
        function fitNotesPanel(){
            var num = $('#searchBox').offset().left + 5;
            $('#notesPanel').css({left: num + "px"});
        }

        setTimeout(fitNotesPanel, 1000);

        $(window).resize(fitNotesPanel);
    });
</script>
</html>