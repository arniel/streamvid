<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');

$show = $db->get_show_info($_GET['id']);
$episodes = $db->get_episodes($_GET['id']);
?>
<div id="subHeader">
    <h1><img src="images/ShowTitle.png" /></h1>
    <?php
    $starredShow = false;
    foreach($_SESSION['prefs']['myStarredShows'] as $showid){
        if($show['tvdb_id'] == $showid){
            $starredShow = true;
            break;
        }
    }

    if($starredShow){
        echo '<img id="star" data-status="active" src="images/icon_star_active.png" />';
    }else{
        echo '<img id="star" data-status="inactive" src="images/icon_star.png" />';
    }
    ?>
</div>
<div id="displayListShow">
    <?php
    echo '<div id="showBanner"><img src="show_images/' . $show['tvdb_id'] . '.banner.jpg" /></div>';
    ?>
    <div id="detailsButton"><span>Show Information</span></div>
    <div id="showDetails">
        <table>
            <?php
            echo '<tr><td>Show Name</td><td>' . $show['show_name'] .'</td></tr>';
            echo '<tr><td>Network</td><td>' . $show['network'] .'</td></tr>';
            echo '<tr><td>Genre</td><td>' . $show['genre'] .'</td></tr>';
            echo '<tr><td>Show Status</td><td>' . $show['status'] .'</td></tr>';
            echo '<tr><td>Start Year</td><td>' . $show['startyear'] .'</td></tr>';
            ?>
        </table>
    </div>
    <?php
    function addEpisode($ep, $epCount){
        if($epCount % 4 == 0){
            if($epCount == 0){
                echo '<tr><td data-epid="' . $ep['episode_id'] . '"><span class="descriptor">Episode ' . $ep['episode'] . '</span> - "' . $ep['name'] . '"</td>';
            }else{
                echo '</tr><tr><td data-epid="' . $ep['episode_id'] . '"><span class="descriptor">Episode ' . $ep['episode'] . '</span> - "' . $ep['name'] . '"</td>';
            }
        }else{
            echo '<td data-epid="' . $ep['episode_id'] . '"><span class="descriptor">Episode ' . $ep['episode'] . '</span> - "' . $ep['name'] . '"</td>';
        }
    }

    $currentSeason = 0;
    $episodeCounter = 0;

    foreach($episodes as $episode){
        if($episode['season'] != $currentSeason){
            if($currentSeason == 0){
                echo '<h3>Season ' . $episode['season'] . '</h3>';
            }else{
                if((($episodeCounter-1) % 4) != 0){
                    echo '</tr>';
                }

                echo '</table><h3>Season ' . $episode['season'] . '</h3>';
            }

            echo '<table class="seasonTable" cellspacing="20">';

            $currentSeason = $episode['season'];

            $episodeCounter = 0;
        }

        addEpisode($episode, $episodeCounter);

        $episodeCounter++;
    }

    echo '</table><br/><br/>';
    ?>
</div>
<script type="text/javascript">
    $('img#star').click(function(){
        function getUrlVars()
        {
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for(var i = 0; i < hashes.length; i++)
            {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        }

        if($(this).data('status') == 'active'){
            $(this).attr("src","images/icon_star.png");
            $(this).data('status', 'inactive');

            $.post("index.php", {
                command: 'removeStarredShow',
                starredShow: getUrlVars()['id']
            });
        }else{
            $(this).attr("src","images/icon_star_active.png");
            $(this).data('status', 'active');

            $.post("index.php", {
                command: 'addStarredShow',
                starredShow: getUrlVars()['id']
            });
        }
    });

    $('#detailsButton span').click(function(){
        $('#showDetails').toggle();
    });

    $('table.seasonTable td').click(function(){
        window.location.href = 'index.php?view=episode&id=' + $(this).data('epid');
    });
</script>