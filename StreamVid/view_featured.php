<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');
?>
<div id="subHeader">
    <h1><img src="images/HomeTitle.png" /></h1>
</div>
<div id="displayListFeatured">
    <h3>Featured Movies</h3>
    <table id="favoriteMovies">
        <tr>
            <?php
            if($result2 =  $mysqli_movies->query('SELECT * FROM staff_picks')){
                foreach($result2 as $i=>$row){
                    echo '<td><img data-movieid="' . $row['movieid'] . '" src="movie_images/' . basename($row['poster']) . '" /></td>';
                }
            }
            ?>
        </tr>
    </table>
    <h3>Featured Television</h3>
    <table>
        <?php
        if($result =  $mysqli->query('SELECT * FROM staff_picks')){
            foreach($result as $i=>$row){
                if($i % 2 == 0){
                    echo '<tr><td class="imageCol" data-showid="' . $row['tvdbid'] . '"><img src="show_images/' . $row['tvdbid'] . '.banner.jpg"></td>';
                }else{
                    echo '<td class="imageCol" data-showid="' . $row['tvdbid'] . '"><img src="show_images/' . $row['tvdbid'] . '.banner.jpg"></td></tr>';
                }
            }
        }

        if($result->num_rows % 2 == 1)
            echo '</tr>'
        ?>
    </table>
    <h3>Your Starred Shows</h3>
    <table id="favoriteShows">
        <?php
        $starredShows = array();

        foreach($db->get_shows() as $row){
            foreach($_SESSION['prefs']['myStarredShows'] as $showid){
                if($row['tvdb_id'] == $showid){
                    array_push($starredShows, $row);
                    break;
                }
            }
        }

        if(sizeof($starredShows) > 0){
            foreach($starredShows as $i=>$row){
                if(($i%2)==0){
                    echo '<tr><td data-showid="' . $row['tvdb_id'] . '" class="imageCol"><img class="showBanner" src="show_images/' . $row['tvdb_id'] . '.banner.jpg" /></td>';
                }else{
                    echo '<td data-showid="' . $row['tvdb_id'] . '" class="imageCol"><img class="showBanner" src="show_images/' . $row['tvdb_id'] . '.banner.jpg" /></td></tr>';
                }
            }

            if((sizeof($starredShows)%2)==1){
                echo '</tr>';
            }
        }else{
            echo '<div id="noShows">No starred shows, <a href="index.php?view=tv">Add Some!</a></div>';
        }
        ?>
    </table>
</div>
<script type="text/javascript">
    $('table#favoriteMovies td img').click(function(){
        window.location.href = 'index.php?view=film&id=' + $(this).data('movieid');
    });

    $('td.imageCol').click(function(){
        window.location.href = 'index.php?view=show&id=' + $(this).data('showid');
    });
</script>