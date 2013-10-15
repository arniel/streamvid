<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');
?>
<div id="subHeader">
    <h1><img src="images/MovieTitle.png" /></h1>
</div>
<div id="displayListFilm">
    <?php
    if($moviedbresults = $mysqli_movies->query('SELECT * FROM  metadata WHERE id="' . $_GET['id'] . '"')){
        $movie = $moviedbresults->fetch_row();
        $url = 'moviecontent/' . $movie[7];
    ?>
        <h3><? echo $movie[2]; ?></h3>
        <div id="mediaPlayerWrapper">
            <div id="mediaplayer">EPISODE SHOULD BE HERE</div>
            <script type="text/javascript">
                var fullscreen = false;

                if(<?=($_SESSION['prefs']['defaultFullscreen'] == 'true') ? 'true' : 'false'?>)
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
        <div id="detailsButton"><span>Movie Information</span></div>
        <div id="movieDetails">
            <?php
            echo '<table>';
            echo '<tr><td id="poster" colspan="5"><img src="movie_images/' . basename($movie[6]) . '" /></td></tr>';
            echo '<tr><td>Title</td><td class="secondColumn">' . $movie[2] . '</td></tr>';
            echo '<tr><td>IMDb</td><td class="secondColumn"><a href="http://www.imdb.com/title/' . $movie[1] . '">Link</a></td></tr>';
            echo '<tr><td>Release Date</td><td class="secondColumn">' . $movie[3] . '</td></tr>';
            echo '<tr><td>Runtime</td><td class="secondColumn">' . $movie[4] . '</td></tr>';
            echo '<tr><td>Overview</td><td class="secondColumn">' . $movie[5] . '</td></tr>';
            echo '</table>';
            ?>
        </div>
    <?php
    }
    ?>

</div>
<script type="text/javascript">
    $('#detailsButton span').click(function(){
        $('#movieDetails').toggle();
    });
</script>