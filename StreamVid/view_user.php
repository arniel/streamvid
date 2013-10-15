<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');

require('security/mysqli-streamvid_db.php');
?>
<div id="subHeader">
    <h1><img src="images/UserTitle.png" /></h1>
</div>
<div id="displayListUser">
    <h3>User Options</h3>
    <table id="userOptions">
        <tr>
            <td>Default TV View:</td>
            <td>
                <form action="">
                    <input type="radio" name="defaulttvview" value="alphabetize" <? if($_SESSION['prefs']['defaultTV'] == 'alphabetize') echo 'checked'; ?>>Alphabetize
                    <input type="radio" name="defaulttvview" value="shuffle" <? if($_SESSION['prefs']['defaultTV'] == 'shuffle') echo 'checked'; ?>>Shuffle
                    <input type="radio" name="defaulttvview" value="starred" <? if($_SESSION['prefs']['defaultTV'] == 'starred') echo 'checked'; ?>>Starred
                </form>
            </td>
        </tr>
        <tr>
            <td>Autoplay Episode:</td>
            <td>
                <form action="">
                    <input type="radio" name="autoplay" value="true" <? if($_SESSION['prefs']['autoPlay'] == 'true') echo 'checked'; ?>>Yes
                    <input type="radio" name="autoplay" value="false" <? if($_SESSION['prefs']['autoPlay'] == 'false') echo 'checked'; ?>>No
                </form>
            </td>
        </tr>
        <tr>
            <td>Open with Fullscreen:</td>
            <td>
                <form action="">
                    <input type="radio" name="fullscreen" value="true" <? if($_SESSION['prefs']['defaultFullscreen'] == 'true') echo 'checked'; ?>>Yes
                    <input type="radio" name="fullscreen" value="false" <? if($_SESSION['prefs']['defaultFullscreen'] == 'false') echo 'checked'; ?>>No
                </form>
            </td>
        </tr>
        <tr>
            <td>Default Player Type:</td>
            <td>
                <form action="">
                    <input type="radio" name="player" value="html5" <? if($_SESSION['prefs']['defaultPlayer'] == 'html5') echo 'checked'; ?>>HTML5
                    <input type="radio" name="player" value="flash" <? if($_SESSION['prefs']['defaultPlayer'] == 'flash') echo 'checked'; ?>>Flash
                </form>
            </td>
        </tr>
    </table>
    <h3>My Starred Shows</h3>
    <table id="myStarredShows" cellspacing="10">
        <?php
        if(isset($_SESSION['prefs']['myStarredShows'])){
            foreach($_SESSION['prefs']['myStarredShows'] as $i=>$showid){
                $show = $db->get_show_info($showid);

                if($i % 4 == 0){
                    if($i == 0){
                        echo '<tr><td><input type="checkbox" name="starred" value="' . $show['tvdb_id'] . '" checked>' . $show['show_name'] . '</td>';
                    }else{
                        echo '</tr><tr><td><input type="checkbox" name="starred" value="' . $show['tvdb_id'] . '" checked>' . $show['show_name'] . '</td>';
                    }
                }else{
                    echo '<td><input type="checkbox" name="starred" value="' . $show['tvdb_id'] . '" checked>' . $show['show_name'] . '</td>';
                }
            }

            if(sizeof($_SESSION['prefs']['myStarredShows']) == 0){
                echo '<tr><td>No starred shows, <a href="index.php?view=tv">Add Some!</a></td></tr>';
            }
        }else{
            echo '<tr><td>No starred shows, <a href="index.php?view=tv">Add Some!</a></td></tr>';
        }
        ?>
    </table>
    <div id="saveChanges"><input type="button" value="Save Changes"/></div>
</div>
<script type="text/javascript">
    $('#saveChanges input').click(function(){
        var starredShows = [];

        $('table#myStarredShows td input').each(function(){
            if($(this).is(":checked")){
                starredShows.push($(this).val());
            }
        });

        var fullPrefs = {};
        fullPrefs['myStarredShows'] = JSON.stringify(starredShows);
        fullPrefs['defaultTV'] = $('table#userOptions input:radio[name="defaulttvview"]:checked').val();
        fullPrefs['autoPlay'] = $('table#userOptions input:radio[name="autoplay"]:checked').val();
        fullPrefs['defaultFullscreen'] = $('table#userOptions input:radio[name="fullscreen"]:checked').val();
        fullPrefs['defaultPlayer'] = $('table#userOptions input:radio[name="player"]:checked').val();

        $.post("index.php", {
            command: 'submitPrefs',
            myStarredShows: fullPrefs['myStarredShows'],
            defaultTV: fullPrefs['defaultTV'],
            autoPlay: fullPrefs['autoPlay'],
            defaultFullscreen: fullPrefs['defaultFullscreen'],
            defaultPlayer: fullPrefs['defaultPlayer']
        });
    });

    $('table#userOptions input:radio[name="player"]').change(function(){
        if($(this).val() == 'flash'){
            $('table#userOptions input:radio[name="fullscreen"][value="false"]').prop("checked", true);

            $('table#userOptions input:radio[name="fullscreen"]').attr('disabled', true);
        }else{
            $('table#userOptions input:radio[name="fullscreen"]').attr('disabled', false);
        }
    });

    if($('table#userOptions input:radio[name="player"]:checked').val() == 'flash'){
        $('table#userOptions input:radio[name="fullscreen"][value="false"]').prop("checked", true);

        $('table#userOptions input:radio[name="fullscreen"]').attr('disabled', true);
    }else{
        $('table#userOptions input:radio[name="fullscreen"]').attr('disabled', false);
    }
</script>