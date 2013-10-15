<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');

function ordinal_to_datetime($ordinal){
    $begin = new DateTime('0001-01-00');
    $date = new DateInterval("P".$ordinal."D");
    $begin->add($date);
    return $begin;
}

function generateURI($ep, $secret, $protectedPath, $baseDir){
    $hexTime = dechex(time());
    $fileName = str_replace($baseDir, '', $ep['location']);
    $token = md5($secret . $fileName. $hexTime);
    $url = $protectedPath . $token. "/" . $hexTime . $fileName;
    return $url;
}
?>
<div id="subHeader">
    <h1><img src="images/EpisodeTitle.png" /></h1>
</div>
<div id="displayListEpisode">
    <?php
    $episode = $db->get_episode_info($_GET['id']);

    echo '<h3><a href="index.php?view=show&id=' . $episode['showid'] . '"><< Back to Show</a></h3>';

    $url = generateURI($episode, $config['AuthTokenSecret'], $config['AuthTokenPrefix'], $config['BaseTVDir']);

    $nav = $db->get_episode_nav($_GET['id']);
    ?>
    <h3><?=$episode['show_name']?>: <?=$episode['season']?>x<?=$episode['episode']?> - "<?=$episode['name']?>"</h3>
    <div id="mediaPlayerWrapper">
        <div id="mediaplayer">EPISODE SHOULD BE HERE</div>
        <script type="text/javascript">
            var fullscreen = false;

            if(window.location.href.indexOf('&window=fullscreen') > -1 || <?=($_SESSION['prefs']['defaultFullscreen'] == 'true') ? 'true' : 'false'?>)
                fullscreen = true;


            jwplayer("mediaplayer").setup({
                file: "<?=$url?>",
                autostart: <?=($_SESSION['prefs']['autoPlay'] == 'true') ? 'true' :  'false'?>,
                'provider': 'http',
                'http.startparam': 'start',
                'menu': false,
                height: "400px",
                width: "100%",
                modes: <? if($_SESSION['prefs']['defaultPlayer'] == 'flash') echo'[{type: \'flash\', src: \'player.swf\'},{type: \'html5\'}]';
                          else echo'[{type: \'html5\'},{type: \'flash\', src: \'player.swf\'}]'; ?>,
                events: {
                    onComplete: function() {
                        if(jwplayer().getFullscreen()){

                        }
                        <? if($nav['next'] != ''){?>
                        window.location.href = "index.php?view=episode&id=<?=$nav['next']?>&window=fullscreen";
                        <? } ?>
                    },
                    onPlay: function(){
                        if(fullscreen){
                            jwplayer().setFullscreen();
                            fullscreen = false;
                        }
                    }
                }

            });
        </script>
    </div>
    <div id="detailsButton"> <span>Episode Information</span></div>
    <div id="episodeDetails">
        <?php
        echo '<table>';
        echo '<tr><td>Show</td><td>' . $episode['show_name'] . '</td></tr>';
        echo '<tr><td>Season</td><td>' . $episode['season'] . '</td></tr>';
        echo '<tr><td>Episode</td><td>' . $episode['episode'] . '</td></tr>';
        echo '<tr><td>Title</td><td>' . $episode['name'] . '</td></tr>';
        echo '<tr><td>Air Date</td><td>' . ordinal_to_datetime($episode['airdate'])->format('Y-m-d') . '</td></tr>';
        echo '<tr><td>Description</td><td>' . $episode['description'] . '</td></tr>';
        echo '</table>';
        ?>
    </div>
    <?php

    echo '<div id="episodeFooter">';

    if($nav['prev'] != ''){
        echo '<a id="leftLink" href="index.php?view=episode&id=' . $nav['prev'] . '">< Previous</a>';
    }else{
        echo '<a id="leftLink" class="disabled" href="#">< Previous</a>';
    }

    if($nav['next'] != ''){
        echo '<a id="rightLink" href="index.php?view=episode&id=' . $nav['next'] . '">Next ></a>';
    }else{
        echo '<a id="rightLink" class="disabled" href="#">Next ></a>';
    }

    echo '</div>'
    ?>
</div>
<script type="text/javascript">
    $('#detailsButton span').click(function(){
        $('#episodeDetails').toggle();
    });
</script>