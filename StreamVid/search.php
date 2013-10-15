<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');
?>
<div id="subHeader">
    <h1><img src="images/SearchTitle.png" /></h1>
</div>
<div id="displayListSearch">
    <?php
    if(isset($_GET['q'])){
        $queryString = strtolower($_GET['q']);

        $movies = $mysqli_movies->query('SELECT * FROM metadata ORDER BY title');
        $shows = $db->get_shows();
        $episodes = $db->get_all_episodes();

        echo '<h3>Movie Results</h3><table>';

        foreach($movies as $row){
            if(strpos(strtolower($row['title']), $queryString) > -1) {
                echo '<tr><td><div class="movie resultWrapper" data-movieid="' . $row['id'] . '"><span class="string">' . $row['title'] . '</span><img src="movie_images/' . basename($row['poster']) . '" /></div></td></tr>';
            }
        }

        echo '</table><h3>Show Results</h3><table>';

        foreach($shows as $row){
            if(strpos(strtolower($row['show_name']), $queryString) > -1) {
                echo '<tr><td><div class="show resultWrapper" data-showid="' . $row['tvdb_id'] . '"><span class="string">' . $row['show_name'] . '</span><img src="show_images/' . $row['tvdb_id'] . '.banner.jpg" /></div></td></tr>';
            }
        }

        echo '</table><h3>Episode Results</h3><table>';

        foreach($episodes as $row){
            if(strpos(strtolower($row['name']), $queryString) > -1) {
                echo '<tr><td><div class="episode resultWrapper" data-epid="' . $row['episode_id'] . '"><span class="string">"' . $row['name'] . '"</span><img src="show_images/' . $row['showid'] . '.banner.jpg" /></div></td></tr>';
            }
        }

        echo '</table>';
    }
    ?>
</div>
<script type="text/javascript">
    $('.movie.resultWrapper').click(function(){
        window.location.href = 'index.php?view=film&id=' + $(this).data('movieid');
    });

    $('.show.resultWrapper').click(function(){
        window.location.href = 'index.php?view=show&id=' + $(this).data('showid');
    });

    $('.episode.resultWrapper').click(function(){
        window.location.href = 'index.php?view=episode&id=' + $(this).data('epid');
    });
</script>