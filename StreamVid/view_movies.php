<?php
if($config['debug'])
    error_reporting(E_ALL); ini_set('display_errors', '1');
?>
<div id="subHeader">
    <h1><img src="images/MoviesTitle.png" /></h1>
</div>
<div id="displayListMovies">
    <?php
    if($result5 = $mysqli_movies->query('SELECT * FROM  metadata ORDER BY title')){
        echo '<table>';

        if($result5->num_rows > 0){
            $i = 0;
            while($row = $result5->fetch_row()){
                if($i % 4 == 0){
                    if($i == 0){
                        echo '<tr><td><img class="moviePoster" data-movieid="' . $row[0] . '" src="movie_images/' . basename($row[6]) . '" /></td>';
                    }else{
                        echo '</tr><tr><td><img class="moviePoster" data-movieid="' . $row[0] . '" src="movie_images/' . basename($row[6]) . '" /></td>';
                    }
                }else{
                    echo '<td><img class="moviePoster" data-movieid="' . $row[0] . '" src="movie_images/' . basename($row[6]) . '" /></td>';
                }

                $i++;
            }
        }

        echo '</table>';
    }
    ?>
</div>
<script type="text/javascript">
    $('.moviePoster').click(function(){
        window.location.href = 'index.php?view=film&id=' + $(this).data('movieid');
    });
</script>